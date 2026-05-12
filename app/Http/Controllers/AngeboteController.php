<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as Request;
use App\Models\Angebot as Entity;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Spatie\Browsershot\Browsershot;
use App\Models\Beschreibung;
use App\Models\Firma;
use App\Services\DocxAngebotService;
use Illuminate\Validation\Rule;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;
use Throwable;

class AngeboteController extends Controller 
{
    protected $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;

    }

    public function index()
    {   
        return view('make_angebote.index');
    }
    
    /*
     * Yajrabox datatables metod
     */
    public function datatable()
    {
        $query = Entity::query()
            ->with('creator')
            ->where('id', '>', 0)
            ->orderBy('created_at', 'desc');

        return datatables($query)
            ->addColumn('actions', function ($entity) {
                return view('make_angebote.partials.table.actions', 
                            ['entity' => $entity]);
            })
            ->editColumn('price', function ($entity) {
                return '€ ' . $entity->formatted_price;
            })
            ->editColumn("date_start", function ($entity) {
                return $entity->date_start ? $entity->date_start->format("d.m.Y") : '-';
            })
            ->editColumn("created_by", function ($entity) {
                return $entity->creator?->name ?? '-';
            })
            ->rawColumns(['actions'])
            ->orderColumn('id_invoice', function ($query, $order) {
                $query->orderBy('id', $order);
            })
            ->setRowAttr([
                'data-id' => function($entity) {
                    return $entity->id;
                }
            ])
            ->make(true);
    }
    
    public function save(Request $request)
    {
        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'adress' => 'required|string|max:255',
            'ort' => 'nullable|string|max:255',
            'uid' => 'nullable|string|max:50',
            'date' => 'required|date',
            'bvh' => 'nullable|string|max:255',
            'auftragsnr' => 'nullable|string|max:255',
            'rechnung_nr' => [
                'nullable',
                'string',
                'max:15',
                Rule::unique('angebote', 'id_invoice')->whereNull('deleted_at'),
            ],
            'ausführungszeit' => 'nullable|string|max:100',
            'invoice_note' => 'nullable|string',
            'total' => 'required|string',
            'html' => 'required|string',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'discount_fixed' => 'nullable',
            'deckungsrucklass_percent' => 'nullable|numeric|min:0|max:100',
            'use_tax' => 'nullable|boolean',
            'abzug_tr1' => 'nullable',
            'abzug_tr_label' => 'nullable|string|max:40',
            'spacing_top' => 'nullable|integer|min:0|max:160',
            'items' => 'nullable|array',
            'items.*.name' => 'nullable|string|max:1000',
            'items.*.qty' => 'nullable',
            'items.*.price' => 'nullable',
            'items.*.total' => 'nullable',
        ], [
            'rechnung_nr.unique' => 'Angebotsnummer bereits vergeben!'
        ]);

        $type = 'angebot';
        $customerName = trim($data['customer_name']);
        $adress = $data['adress'];
        $ort = $data['ort'] ?? null;
        $uid = $data['uid'] ?? null;
        $dateValue = $data['date'];
        $bvh = $data['bvh'] ?? null;
        $auftragsnr = $data['auftragsnr'] ?? null;
        $rechnungNr = $this->resolveAngebotNumber($data['rechnung_nr'] ?? null);
        $ausführungszeit = $data['ausführungszeit'] ?? null;
        $invoiceNote = $data['invoice_note'] ?? null;
        $items = $this->normalizeItems($data['items'] ?? []);

        $totalValue = $items
            ? $this->calculateTotal($items, $data)
            : $this->parseMoney($data['total']);

        $folder = 'angebote';
        Storage::disk('public')->makeDirectory($folder);

        $slug = Str::slug($customerName);
        $number = Str::slug(str_replace('/', '-', $rechnungNr));
        $timestamp = Carbon::now()->format('dm-Hi');

        $filename = $this->getUniqueFileName($folder, "{$number}-{$slug}-{$timestamp}.pdf");
        $html = $this->sanitizePdfHtml($data['html']);
        $pdfBinary = $this->generateOfferPdfBinary(
            $html,
            $data,
            $items,
            $totalValue,
            $rechnungNr,
            $filename
        );

        $storagePath = $folder . '/' . $filename;
        Storage::disk('public')->put($storagePath, $pdfBinary);

        if (!Storage::disk('public')->exists($storagePath)) {
            return response()->json(['error' => 'PDF saving failed'], 500);
        }

        $invoice = DB::transaction(function () use (
            $type,
            $rechnungNr,
            $totalValue,
            $customerName,
            $adress,
            $ort,
            $uid,
            $bvh,
            $auftragsnr,
            $ausführungszeit,
            $invoiceNote,
            $dateValue,
            $filename,
            $items
        ) {
            $invoice = Entity::create([
                'type' => $type,
                'id_invoice' => $rechnungNr,
                'price' => number_format($totalValue, 2, '.', ''),
                'firma' => $customerName,
                'adress' => $adress,
                'ort' => $ort,
                'uid' => $uid,
                'bvh' => $bvh,
                'auftragsnr' => $auftragsnr,
                'ausfuhrungszeit' => $ausführungszeit,
                'note' => $invoiceNote,
                'date_start' => $dateValue,
                'invoice_url' => 'angebote/' . $filename,
                'created_by' => auth()->id(),
            ]);

            foreach ($items as $item) {
                Beschreibung::create([
                    'invoice_type' => 'angebot',
                    'invoice_id' => $invoice->id,
                    'name' => $item['name'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'total' => $item['total'],
                ]);
            }

            return $invoice;
        });

        return response()->json([
            'success' => true,
            'invoice_id' => $invoice->id,
            'pdf_url' => route('angebote.view', $invoice->id)
        ]);
    }

    public function viewPdf($id)
    {
        $angebot = Entity::findOrFail($id);

        $pdfPath = $this->normalizePublicPdfPath($angebot->invoice_url);
        $path = Storage::disk('public')->path($pdfPath);
        $filename = 'angebot-' . Str::slug(str_replace('/', '-', $angebot->id_invoice), '-') . '.pdf';

        if (!file_exists($path)) {
            abort(404, 'PDF nije pronađen');
        }

        if ($this->request->boolean('download')) {
            return response()->download($path, $filename, [
                'Content-Type' => 'application/pdf',
            ]);
        }

        if (! $this->request->boolean('raw')) {
            return view('pdf.viewer', [
                'title' => __('Angebot') . ' ' . $angebot->id_invoice,
                'fileName' => $filename,
                'pdfUrl' => route('angebote.pdf', $angebot->id),
                'downloadUrl' => $this->request->fullUrlWithQuery(['download' => 1]),
            ]);
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    public function pdfFile($id)
    {
        $angebot = Entity::findOrFail($id);
        $pdfPath = $this->normalizePublicPdfPath($angebot->invoice_url);
        $path = Storage::disk('public')->path($pdfPath);
        $filename = 'angebot-' . Str::slug(str_replace('/', '-', $angebot->id_invoice), '-') . '.pdf';

        if (!file_exists($path)) {
            abort(404, 'PDF nije pronađen');
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    public function downloadPdf(Request $request)
    {
        $pdfPath = $this->normalizePublicPdfPath($request->query('path'));

        if ($pdfPath === '' || str_contains($pdfPath, '..')) {
            abort(404, 'PDF nije pronađen');
        }

        $path = Storage::disk('public')->path($pdfPath);

        if (!file_exists($path)) {
            abort(404, 'PDF nije pronađen');
        }

        return response()->download($path, basename($pdfPath), [
            'Content-Type' => 'application/pdf',
        ]);
    }
    
    private function normalizePublicPdfPath(?string $path): string
    {
        $path = ltrim((string) $path, '/');

        foreach (['storage/', 'public/'] as $prefix) {
            if (str_starts_with($path, $prefix)) {
                $path = substr($path, strlen($prefix));
            }
        }

        return $path;
    }
    
    public function delete(Entity $entity)
    {
        $entity->delete();

        return response()->json([
            'success' => true,
            'message' => 'Angebot erfolgreich gelöscht!'
        ]);
    }

    // Funkcija koja dodaje sufiks ako fajl već postoji u storage
    protected function getUniqueFileName($folder, $filename)
    {
        $disk = 'public';
        $original = pathinfo($filename, PATHINFO_FILENAME);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        $counter = 1;
        $path = "{$folder}/{$filename}";

        while (Storage::disk($disk)->exists($path)) {
            $filename = $original . '-' . $counter . '.' . $extension;
            $path = "{$folder}/{$filename}";
            $counter++;
        }

        return $filename;
    }

    private function resolveAngebotNumber(?string $requestedNumber): string
    {
        $requestedNumber = trim((string) $requestedNumber);

        if ($requestedNumber !== '') {
            return $requestedNumber;
        }

        $last = Entity::where('type', 'angebot')
            ->where('id_invoice', 'like', 'A-%')
            ->orderByDesc('id')
            ->first();

        $lastNumber = 0;

        if ($last && preg_match('/^A-(\d+)$/', (string) $last->id_invoice, $matches)) {
            $lastNumber = (int) $matches[1];
        }

        return 'A-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }

    private function normalizeItems(array $items): array
    {
        return collect($items)
            ->map(function ($item) {
                $name = trim((string) ($item['name'] ?? ''));
                $qty = trim((string) ($item['qty'] ?? ''));
                $price = trim((string) ($item['price'] ?? ''));
                $total = trim((string) ($item['total'] ?? ''));

                return [
                    'name' => $name,
                    'qty' => $qty,
                    'price' => $price,
                    'total' => number_format($this->parseMoney($total), 2, '.', ''),
                ];
            })
            ->filter(function ($item) {
                return $item['name'] !== ''
                    || ($item['qty'] !== '' && $this->parseMoney($item['qty']) !== 0.0)
                    || $this->parseMoney($item['price']) > 0
                    || $this->parseMoney($item['total']) > 0;
            })
            ->values()
            ->all();
    }

    private function calculateTotal(array $items, array $data): float
    {
        $subtotal = collect($items)->sum(fn ($item) => $this->parseMoney($item['total']));

        $discountPercent = (float) ($data['discount_percent'] ?? 0);
        $discountFixed = $this->parseMoney($data['discount_fixed'] ?? 0);
        $deckungsrucklassPercent = (float) ($data['deckungsrucklass_percent'] ?? 0);
        $abzugTr1 = $this->parseMoney($data['abzug_tr1'] ?? 0);
        $useTax = filter_var($data['use_tax'] ?? false, FILTER_VALIDATE_BOOLEAN);

        if ($discountPercent > 0) {
            $subtotal -= $subtotal * ($discountPercent / 100);
        }

        if ($discountFixed > 0) {
            $subtotal -= $discountFixed;
        }

        if ($deckungsrucklassPercent > 0) {
            $subtotal -= $subtotal * ($deckungsrucklassPercent / 100);
        }

        if ($useTax) {
            $subtotal += $subtotal * 0.20;
        }

        if ($abzugTr1 > 0) {
            $subtotal -= $abzugTr1;
        }

        return round(max($subtotal, 0), 2);
    }

    private function parseMoney($value): float
    {
        $value = trim((string) $value);
        $value = str_replace(['€', ' '], '', $value);

        if ($value === '') {
            return 0.0;
        }

        if (str_contains($value, ',') && str_contains($value, '.')) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        } elseif (str_contains($value, ',')) {
            $value = str_replace(',', '.', $value);
        }

        return is_numeric($value) ? (float) $value : 0.0;
    }

    private function generateOfferPdfBinary(
        string $html,
        array $data,
        array $items,
        float $totalValue,
        string $rechnungNr,
        string $filename
    ): string {
        try {
            return $this->generateOfferPdfBinaryFromDocx($data, $items, $totalValue, $rechnungNr, $filename);
        } catch (Throwable $exception) {
            Log::error('DOCX Angebot PDF generation failed.', [
                'angebot_number' => $rechnungNr,
                'error' => $exception->getMessage(),
            ]);

            if (filter_var(env('ANGEBOTE_PDF_HTML_FALLBACK', false), FILTER_VALIDATE_BOOLEAN)) {
                Log::warning('Falling back to HTML PDF for Angebot.', [
                    'angebot_number' => $rechnungNr,
                ]);

                return $this->generate($html);
            }

            throw $exception;
        }
    }

    private function generateOfferPdfBinaryFromDocx(
        array $data,
        array $items,
        float $totalValue,
        string $rechnungNr,
        string $filename
    ): string {
        $soffice = $this->findSofficeBinary();

        if (! $soffice) {
            throw new \RuntimeException('LibreOffice/soffice nije pronađen.');
        }

        $workDir = storage_path('app/docx-angebote/' . Str::uuid());

        if (! is_dir($workDir)) {
            mkdir($workDir, 0775, true);
        }

        $docxName = pathinfo($filename, PATHINFO_FILENAME) . '.docx';
        $docxPath = $workDir . '/' . $docxName;
        $pdfPath = $workDir . '/' . pathinfo($docxName, PATHINFO_FILENAME) . '.pdf';
        $profileDir = $workDir . '/lo-profile';

        try {
            app(DocxAngebotService::class)->createFromData($docxPath, $this->buildDocxOfferData(
                $data,
                $items,
                $totalValue,
                $rechnungNr
            ));

            $process = new Process([
                $soffice,
                '-env:UserInstallation=file://' . $profileDir,
                '--headless',
                '--convert-to',
                'pdf',
                '--outdir',
                $workDir,
                $docxPath,
            ]);
            $process->setTimeout(60);
            $process->run();

            if (! $process->isSuccessful() || ! file_exists($pdfPath)) {
                throw new \RuntimeException($process->getErrorOutput() ?: 'DOCX to PDF konverzija nije uspjela.');
            }

            $pdf = file_get_contents($pdfPath);
        } finally {
            $this->deleteDirectory($workDir);
        }

        return $pdf;
    }

    private function buildDocxOfferData(array $data, array $items, float $totalValue, string $rechnungNr): array
    {
        $subtotal = collect($items)->sum(fn ($item) => $this->parseMoney($item['total']));
        $discountPercent = (float) ($data['discount_percent'] ?? 0);
        $discountFixed = $this->parseMoney($data['discount_fixed'] ?? 0);
        $deckungsPercent = (float) ($data['deckungsrucklass_percent'] ?? 0);
        $abzugTr1 = $this->parseMoney($data['abzug_tr1'] ?? 0);
        $useTax = filter_var($data['use_tax'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $adjustments = [];
        $running = $subtotal;

        if ($discountPercent > 0) {
            $amount = $running * ($discountPercent / 100);
            $running -= $amount;
            $adjustments[] = [
                'label' => '- ' . $this->formatNumber($discountPercent, 0) . '% Nachlass',
                'amount' => $this->formatMoney($amount),
                'running_total' => $this->formatMoney($running),
            ];
        }

        if ($discountFixed > 0) {
            $running -= $discountFixed;
            $adjustments[] = [
                'label' => '- Pauschale',
                'amount' => $this->formatMoney($discountFixed),
                'running_total' => $this->formatMoney($running),
            ];
        }

        if ($deckungsPercent > 0) {
            $amount = $running * ($deckungsPercent / 100);
            $running -= $amount;
            $adjustments[] = [
                'label' => '- ' . $this->formatNumber($deckungsPercent, 0) . '% Deckungsrücklass',
                'amount' => $this->formatMoney($amount),
                'running_total' => $this->formatMoney($running),
            ];
        }

        if ($useTax) {
            $amount = $running * 0.20;
            $running += $amount;
            $adjustments[] = [
                'label' => '+ 20% MwSt',
                'amount' => $this->formatMoney($amount),
                'running_total' => $this->formatMoney($running),
            ];
        }

        if ($abzugTr1 > 0) {
            $running -= $abzugTr1;
            $adjustments[] = [
                'label' => '- ' . $this->abzugTrLabel($data),
                'amount' => $this->formatMoney($abzugTr1),
                'running_total' => $this->formatMoney($running),
            ];
        }

        return [
            'customer_name' => trim((string) ($data['customer_name'] ?? '')),
            'address' => trim((string) ($data['adress'] ?? '')),
            'ort' => trim((string) ($data['ort'] ?? '')),
            'uid' => trim((string) ($data['uid'] ?? '')),
            'date' => Carbon::parse($data['date'])->format('d.m.Y'),
            'bvh' => trim((string) ($data['bvh'] ?? '')),
            'auftragsnr' => trim((string) ($data['auftragsnr'] ?? '')),
            'number' => $rechnungNr,
            'ausfuehrungszeit' => trim((string) ($data['ausführungszeit'] ?? '')),
            'spacing_top' => (int) ($data['spacing_top'] ?? 20),
            'use_tax' => $useTax,
            'note_html' => (string) ($data['invoice_note'] ?? ''),
            'items' => collect($items)->map(fn ($item) => [
                $item['name'],
                $item['qty'],
                $item['price'],
                $this->formatMoney($this->parseMoney($item['total'])),
            ])->all(),
            'summary' => [
                'subtotal' => $this->formatMoney($subtotal),
                'adjustments' => $adjustments,
                'total' => $this->formatMoney($totalValue),
            ],
        ];
    }

    private function findSofficeBinary(): ?string
    {
        $candidates = array_filter([
            env('SOFFICE_PATH'),
            (new ExecutableFinder())->find('soffice'),
            (new ExecutableFinder())->find('libreoffice'),
            '/Applications/LibreOffice.app/Contents/MacOS/soffice',
        ]);

        foreach ($candidates as $candidate) {
            if (is_executable($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    private function abzugTrLabel(array $data): string
    {
        $label = trim((string) ($data['abzug_tr_label'] ?? ''));

        return $label !== '' ? $label : 'Abz. TR 1';
    }

    private function formatMoney(float $value): string
    {
        return number_format(max($value, 0), 2, ',', '.');
    }

    private function formatNumber(float $value, int $digits = 2): string
    {
        return number_format($value, $digits, ',', '.');
    }

    private function deleteDirectory(string $directory): void
    {
        if (! is_dir($directory)) {
            return;
        }

        foreach (scandir($directory) ?: [] as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $directory . '/' . $item;
            is_dir($path) ? $this->deleteDirectory($path) : @unlink($path);
        }

        @rmdir($directory);
    }

    private function sanitizePdfHtml(string $html): string
    {
        return preg_replace('#<script\b[^>]*>.*?</script>#is', '', $html) ?? $html;
    }

    
    public function autocompleteFirma(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        if (mb_strlen($q) < 2) {
            return $this->autocompleteResponse([]);
        }

        $angebote = Entity::whereNotNull('firma')
            ->where('firma', '!=', '')
            ->where(function ($query) use ($q) {
                $this->applyAutocompleteSearch($query, 'firma', $q);
            })
            ->select('firma')
            ->distinct()
            ->limit(20)
            ->pluck('firma');

        $firme = Firma::whereNotNull('name')
            ->where('name', '!=', '')
            ->where(function ($query) use ($q) {
                $this->applyAutocompleteSearch($query, 'name', $q);
            })
            ->select('name')
            ->distinct()
            ->limit(20)
            ->pluck('name');

        $results = $this->prepareAutocompleteResults($angebote->merge($firme), $q);

        return $this->autocompleteResponse($results);
    }

    public function autocompleteAdress(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        if (mb_strlen($q) < 2) {
            return $this->autocompleteResponse([]);
        }

        $angebote = Entity::whereNotNull('adress')
            ->where('adress', '!=', '')
            ->where(function ($query) use ($q) {
                $this->applyAutocompleteSearch($query, 'adress', $q);
            })
            ->select('adress')
            ->distinct()
            ->limit(20)
            ->pluck('adress');

        $firme = Firma::whereNotNull('address')
            ->where('address', '!=', '')
            ->where(function ($query) use ($q) {
                $this->applyAutocompleteSearch($query, 'address', $q);
            })
            ->select('address')
            ->distinct()
            ->limit(20)
            ->pluck('address');

        $results = $this->prepareAutocompleteResults($angebote->merge($firme), $q);

        return $this->autocompleteResponse($results);
    }
    
    public function autocompleteBeschreibung(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        if (mb_strlen($q) < 2) {
            return $this->autocompleteResponse([]);
        }

        $results = Beschreibung::whereNotNull('name')
            ->where('name', '!=', '')
            ->where(function ($query) use ($q) {
                $this->applyAutocompleteSearch($query, 'name', $q);
            })
            ->select('name')
            ->distinct()
            ->limit(30)
            ->pluck('name');

        $results = $this->prepareAutocompleteResults($results, $q);

        return $this->autocompleteResponse($results);
    }

    private function autocompleteResponse($results)
    {
        return response()->json($results)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    private function applyAutocompleteSearch($query, string $column, string $term): void
    {
        $normalizedTerm = $this->normalizeAutocompleteTerm($term);
        $normalizedColumn = "REPLACE(REPLACE(REPLACE(REPLACE(LOWER({$column}), 'ä', 'ae'), 'ö', 'oe'), 'ü', 'ue'), 'ß', 'ss')";

        $query->where($column, 'LIKE', "%{$term}%")
            ->orWhereRaw("{$normalizedColumn} LIKE ?", ["%{$normalizedTerm}%"]);
    }

    private function prepareAutocompleteResults($values, string $term)
    {
        $normalizedTerm = $this->normalizeAutocompleteTerm($term);

        return collect($values)
            ->filter(fn ($value) => trim((string) $value) !== '')
            ->filter(function ($value) use ($term, $normalizedTerm) {
                $value = (string) $value;

                return mb_stripos($value, $term) !== false
                    || str_contains($this->normalizeAutocompleteTerm($value), $normalizedTerm);
            })
            ->unique(fn ($value) => $this->normalizeAutocompleteTerm((string) $value))
            ->values()
            ->take(10);
    }

    private function normalizeAutocompleteTerm(string $value): string
    {
        $value = mb_strtolower(trim($value));

        return str_replace(
            ['ä', 'ö', 'ü', 'ß'],
            ['ae', 'oe', 'ue', 'ss'],
            $value
        );
    }
   
    public function generate($html = null)
    {
        $isHttpRequest = $html === null;
        $html = $isHttpRequest ? (string) $this->request->input('html', '') : (string) $html;

        if ($html === '') {
            abort(422, 'HTML dokument nije poslat.');
        }

        // Putanja do slike u public folderu
        $logoPath = public_path('img/cist-beli-logo.jpg');

        // Pretvori sliku u Base64 za PDF
        if (file_exists($logoPath)) {
            $logoBase64 = base64_encode(file_get_contents($logoPath));
            $html = str_replace(
                'img/cist-beli-logo.jpg',
                'data:image/png;base64,' . $logoBase64,
                $html
            );
        }

        $fullHtml = <<<HTML
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Angebot PDF</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        html,
        body {
            width: 794px;
            margin: 0;
            padding: 0;
            background: #fff;
            color: #000;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            font-weight: 350;
            letter-spacing: 0.08px;
            -webkit-font-smoothing: antialiased;
            text-rendering: geometricPrecision;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .a4-wrapper,
        .angebot-preview-pages {
            width: 794px;
        }

        .a4-preview {
            width: 794px;
            height: 1123px;
            padding: 5mm 16mm 22mm 16mm;
            box-sizing: border-box;
            background: #fff;
            color: #000;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            font-weight: 350;
            letter-spacing: 0.08px;
            -webkit-font-smoothing: antialiased;
            text-rendering: geometricPrecision;
            position: relative;
            overflow: hidden;
            box-shadow: none !important;
            page-break-after: always;
            break-after: page;
        }

        .a4-preview:last-child {
            page-break-after: auto;
            break-after: auto;
        }

        .angebot-page-content {
            position: relative;
            z-index: 1;
            max-height: calc(1123px - 5mm - 42mm);
            padding-bottom: 24mm;
        }

        .header-a4 {
            height: 215px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            position: relative;
        }

        .company-logo {
            width: 285px;
            margin-left: auto;
            margin-right: 4mm;
        }

        .company-logo img {
            width: 100%;
            display: block;
        }

        .company-text {
            display: flex;
            justify-content: flex-end;
            width: 100%;
            transform: translateX(6mm);
        }

        .company-text-left {
            width: 18%;
        }

        .company-text-right {
            width: 29%;
        }

        .company-text-left p,
        .company-text-right p {
            margin: 3px 0;
            line-height: 1.2;
            color: #1a64a2;
            font-size: 11px;
        }

        .firma {
            margin-top: 5px;
        }

        .customer-lead {
            max-width: 350px;
            margin: 0 0 5px 0;
            font-size: 10pt;
            font-weight: 700;
            word-wrap: break-word;
        }

        .customer-address {
            margin: 6px 0 0 0;
            line-height: 1.08;
            font-size: 10pt;
        }

        .customer-address + .customer-address {
            margin-top: 2px;
        }

        .firma-hr {
            width: 350px;
            max-width: 100%;
            height: 1px;
            background-color: #000;
            margin: 5px 0;
        }

        .customer-meta {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            font-size: 10pt;
        }

        .customer {
            margin-top: 5px;
            font-size: 10pt;
        }

        .customer p {
            margin: 0 0 4px 0;
            line-height: 1.05;
        }

        .angebot-title-line {
            font-weight: 700;
        }

        .page-continuation {
            margin: 0 0 10px 0;
            font-size: 10pt;
            font-weight: 500;
            text-align: right;
            color: #555;
        }

        .invoice-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            border: 0.6px solid #333;
            font-size: 10pt;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: 350;
        }

        .invoice-table col.col-desc {
            width: calc(100% - 300px);
        }

        .invoice-table col.col-qty,
        .invoice-table col.col-price,
        .invoice-table col.col-total {
            width: 100px;
        }

        .invoice-table th,
        .invoice-table td {
            padding: 4px 8px;
            border-bottom: 0.6px solid #333;
            line-height: 1.25;
            vertical-align: middle;
        }

        .invoice-table th {
            font-weight: 500;
        }

        .invoice-table th:not(:first-child),
        .invoice-table td:not(:first-child) {
            border-left: 0.6px solid #333;
        }

        .invoice-table th:first-child,
        .invoice-table td:first-child {
            text-align: left !important;
            word-break: break-word;
        }

        .invoice-table td:nth-child(2),
        .invoice-table td:nth-child(3),
        .invoice-table td:nth-child(4) {
            text-align: center;
            white-space: nowrap;
        }

        .invoice-table .amount-cell {
            display: flex;
            justify-content: space-between;
            gap: 8px;
        }

        .offer-summary-wrap {
            margin-right: 9px;
        }

        .offer-summary {
            width: fit-content;
            min-width: 300px;
            max-width: 100%;
            margin-top: 30px;
            margin-left: auto;
            font-size: 10pt;
        }

        .summary-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: baseline;
            column-gap: 14px;
            margin-bottom: 2px;
        }

        .summary-row > span:first-child {
            margin-left: 50px;
        }

        .summary-divider {
            margin-left: 50px;
        }

        .summary-amount {
            min-width: 100px;
            width: max-content;
            display: inline-grid;
            grid-template-columns: auto auto;
            justify-content: end;
            justify-self: end;
            white-space: nowrap;
        }

        .summary-running-total {
            margin-top: -1px;
            margin-bottom: 5px;
        }

        .summary-running-total .summary-amount {
            padding-top: 2px;
            border-top: 1px solid #777;
        }

        .summary-total {
            font-weight: 500;
            font-size: 11pt;
        }

        .summary-total-value {
            column-gap: 8px;
        }

        .summary-total-value {
            border-bottom: 2px double #000;
            padding-bottom: 2px;
            white-space: nowrap;
        }

        .description-left {
            margin-top: 18px;
            margin-left: 24px;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            width: 385px;
            color: #000;
            font-weight: 350;
            line-height: 1.25;
            overflow-wrap: break-word;
        }

        .a4-preview .description-left.preview-note {
            min-height: 0;
        }

        .description-left p,
        .a4-preview .description-left.preview-note p {
            margin: 0 0 4px 0;
        }

        .description-left ul,
        .description-left ol,
        .a4-preview .description-left.preview-note ul,
        .a4-preview .description-left.preview-note ol {
            margin: 0 0 4px 0;
            padding-left: 20px;
            list-style-position: outside;
        }

        .description-left ul,
        .a4-preview .description-left.preview-note ul {
            list-style-type: disc;
        }

        .description-left ol,
        .a4-preview .description-left.preview-note ol {
            list-style-type: decimal;
        }

        .description-left li,
        .a4-preview .description-left.preview-note li {
            display: list-item;
            margin: 0 0 3px 0;
            padding-left: 2px;
            list-style: inherit;
        }

        .description-left strong,
        .description-left b,
        .a4-preview .description-left.preview-note strong,
        .a4-preview .description-left.preview-note b {
            font-weight: 700 !important;
        }

        .description-left em,
        .description-left i,
        .a4-preview .description-left.preview-note em,
        .a4-preview .description-left.preview-note i {
            font-style: italic !important;
        }

        .description-left u,
        .a4-preview .description-left.preview-note u {
            text-decoration: underline !important;
        }

        .description-left .ql-size-small,
        .a4-preview .description-left.preview-note .ql-size-small {
            font-size: 8pt;
        }

        .description-left .ql-size-large,
        .a4-preview .description-left.preview-note .ql-size-large {
            font-size: 13pt;
        }

        .description-left .ql-size-huge,
        .a4-preview .description-left.preview-note .ql-size-huge {
            font-size: 18pt;
        }

        .a4-preview .description-left.preview-note ol > li::before,
        .a4-preview .description-left.preview-note ul > li::before {
            content: none !important;
        }

        .reverse-vat-note {
            width: 100%;
            margin-top: 50px;
            font-size: 9pt;
            color: #000;
            text-align: center;
        }

        .invoice-footer {
            position: absolute;
            bottom: 14mm;
            left: 16mm;
            right: 16mm;
            min-height: 8mm;
            z-index: 50;
            background: #fff;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
            font-weight: 350;
            line-height: 1.2;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            text-align: center;
            color: #000;
            -webkit-font-smoothing: antialiased;
            text-rendering: geometricPrecision;
        }

        .page-counter {
            position: absolute;
            right: 0;
            bottom: -5mm;
            display: none;
            color: #888;
            text-align: right;
        }
    </style>
</head>
<body>
    {$html}
</body>
</html>
HTML;

        $pdf = Browsershot::html($fullHtml)
                ->noSandbox()
                ->format('A4')
                ->showBackground()
                ->pdf();

        if ($isHttpRequest) {
            return response($pdf, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="angebot.pdf"',
            ]);
        }

        return $pdf;
    }
}
