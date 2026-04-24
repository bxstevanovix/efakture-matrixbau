<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as Request;
use App\Models\Rechnung as Entity;

use App\Http\Resources\JsonResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Firma;
use Spatie\Browsershot\Browsershot;
use App\Models\Beschreibung;

class RechnungController extends Controller 
{
    protected $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;

    }

    public function index()
    {   
        $firme = Firma::orderBy('name')->get();
        return view('make_rechnung.index', compact('firme'));
    }
    
    /*
     * Yajrabox datatables metod
     */
    public function datatable()
    {
        $query = Entity::query()->where('id', '>', 0);

        return datatables($query)
            ->editColumn('id_invoice', function ($entity) {
                $typeMap = [
                    'rechnung' => 'R',
                    'teilrechnung' => 'T',
                    'schlussrechnung' => 'S'
                ];

                return $typeMap[$entity->type] . ' - ' . $entity->id_invoice ;
            })

            ->addColumn('actions', function ($entity) {
                return view('make_rechnung.partials.table.actions', 
                            ['entity' => $entity]);
            })
            ->editColumn('price', function ($entity) {
                return "€ " . $entity->price ;
            })
            ->editColumn("date_start", function ($entity) {
                return $entity->date_start->format("d.m.Y");
            })
            ->editColumn("created_by", function ($entity) {
                $user = User::find($entity->created_by);
                return $user ? $user->name : '-';
            })
            ->orderColumn('id_invoice', function ($query, $order) {
                $query->orderBy('id', $order); // 👈 SORT PO ID
            })
            ->rawColumns(['actions', 'image'])
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
            'type' => 'required|in:rechnung,teilrechnung,schlussrechnung',
            'customer_name' => 'required|string|max:255',
            'adress' => 'required|string|max:255',
            'ort' => 'nullable|string|max:255',
            'uid' => 'nullable|string|max:50',
            'date' => 'required|date',
            'bvh' => 'nullable|string|max:255',
            'auftragsnr' => 'nullable|string|max:255',
            'rechnung_nr' => 'required|string|max:50|unique:rechnungen,id_invoice',
            'ausführungszeit' => 'nullable|string|max:100',
            'invoice_note' => 'nullable|string',
            'total' => 'required',
            'html' => 'required|string'
        ]);

        $type = $data['type'];
        $customerName = trim($data['customer_name']);
        $adress = $data['adress'];
        $ort = $data['ort'] ?? null;
        $uid = $data['uid'] ?? null;
        $dateValue = $data['date'];
        $bvh = $data['bvh'] ?? null;
        $auftragsnr = $data['auftragsnr'] ?? null;
        $rechnungNr = $data['rechnung_nr'] ?? null;
        $ausführungszeit = $data['ausführungszeit'] ?? null;
        $invoiceNote = $data['invoice_note'] ?? null;

        // total format
        $totalValue = str_replace('.', '', $data['total']);
        $totalValue = str_replace(',', '.', $totalValue);
        $totalValue = floatval($totalValue);

        $html = $data['html'];

        $pdfBinary = $this->generate($html);

        $typeMap = [
            'rechnung' => 'rechnungen',
            'teilrechnung' => 'teilrechnungen',
            'schlussrechnung' => 'schlussrechnungen'
        ];

        // folder
        $folder = $typeMap[$type];
        Storage::disk('public')->makeDirectory($folder);

        $slug = Str::slug($customerName);
        $number = Str::slug(str_replace('/', '-', $rechnungNr));
        $timestamp = Carbon::now()->format('dm-Hi');

        // filename
        $filename = $this->getUniqueFileName(
            storage_path('app/public/' . $folder), "{$number}-{$slug}-{$timestamp}.pdf"
        );

        $storagePath = $folder . '/' . $filename;
        Storage::disk('public')->put($storagePath, $pdfBinary);

        if (!Storage::disk('public')->exists($storagePath)) {
            return response()->json(['error' => 'PDF saving failed'], 500);
        }

        // snimi invoice
        $invoice = new Entity();

        $invoice->type = $type;
        $invoice->id_invoice = $rechnungNr;
        $invoice->price = $totalValue;
        $invoice->firma = $customerName;
        $invoice->adress = $adress;
        $invoice->ort = $ort;
        $invoice->uid = $uid;
        $invoice->bvh = $bvh;
        $invoice->auftragsnr = $auftragsnr;
        $invoice->ausfuhrungszeit = $ausführungszeit;
        $invoice->note = $invoiceNote;

        $invoice->date_start = $dateValue;
        
        $invoice->invoice_url = $folder . '/' . $filename;
        $invoice->created_by = auth()->id();

        $invoice->save();

        if($request->items){
            foreach ($request->items as $item) {

                Beschreibung::create([
                    'invoice_type' => 'angebot',
                    'invoice_id' => $invoice->id,

                    'name'  => $item['name'] ?? null,
                    'qty'   => $item['qty'] ?? 0,
                    'price' => $item['price'] ?? 0,
                    'total' => $item['total'] ?? 0,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'invoice_id' => $invoice->id,
            'pdf_url' => route('rechnung.view', $invoice->id)
        ]);
    }

    public function viewPdf($id)
    {
        $angebot = Entity::findOrFail($id);

        $path = storage_path('app/public/' . $angebot->invoice_url);

        if (!file_exists($path)) {
            abort(404, 'PDF nije pronađen');
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Rechnung_'.$angebot->id_invoice.'.pdf"',
        ]);
    }
    
    public function delete(Entity $entity)
    {
        $entity->delete();

        return response()->json([
            'success' => true,
            'message' => 'Racun je uspešno obrisan!'
        ]);
    }

    /**
     * Funkcija koja dodaje sufiks ako fajl već postoji u storage
     */
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

    public function autocompleteAdress(Request $request)
    {
        $term = $request->get('q');

        return Entity::where('adress', 'LIKE', "%$term%")
            ->select('adress')
            ->distinct()
            ->limit(10)
            ->pluck('adress');
    }

    public function generate($html)
    {
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

        $fullHtml = "
        <html>
        <head>
            <meta charset='utf-8'>
            <style>
            body {
                font-family: Arial, sans-serif;
            }


            @page {
                margin: 5mm 15mm 5mm 15mm;
            }

            .a4-preview {
                font-family: Arial, sans-serif;
            }
		
            .a4-wrapper {
                zoom: 1;
            }

            .a4-preview {
                background: #fff;
                box-sizing: border-box;
                position: relative;  
                color: #000 !important;
                font-size:12px;
            }

            .header-a4 {
                height: 210px;
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
                position: relative;
            }

            .company-logo {
                width: 285px;
                margin-left: auto; /* guramo logo desno */
            }

            .company-logo img {
                width: 100%;
                display: block;
            }

            .company-text {
                display: flex;
                justify-content: flex-end;
                width: 100%;
                font-size: 10px;
                gap: 1%;
            }

            .company-text-left {
                width: 15%;
            }

            .company-text-right {
                width: 23%;
            }

            .company-text-left p,
            .company-text-right p {
                margin: 3px 0;
                line-height: 1.2;
                color: #1a64a2;
                font-size: 10px;
            }

            .firma {
                margin-top: 5px;
            }

                .customer-lead {
                    font-size: 12px;
                    text-align: left;
                    font-weight: bold;
                    margin-bottom: 5px;
                }

                .firma-hr {
                    width: 350px;
                    max-width: 100%;
                    height: 1px;
                    background-color: black;
                    margin: 5px 0;
                }
                        
                .invoice-table{
                    width:100%;
                    table-layout:fixed;
                    border-collapse:collapse;
                    border: 1px solid black;
                }

                /* širine kolona */
                .invoice-table col.col-desc{
                    width: calc(100% - 300px);
                }

                .invoice-table col.col-qty{
                    width: 100px;
                }

                .invoice-table col.col-price{
                    width: 100px;
                }

                .invoice-table col.col-total{
                    width: 100px;
                }

                /* cell stil */
                .invoice-table th,
                .invoice-table td{
                    padding:4px 8px;
                    border-bottom: 1px solid black;
                    line-height: 1.25;
                    vertical-align:middle;
                }

                /* alignment */
                .invoice-table td:nth-child(2),
                .invoice-table td:nth-child(3),
                .invoice-table td:nth-child(4){
                    text-align:center;
                    white-space:nowrap;
                    border-left: 1px solid black;
                }

                /* opis kolona */
                .invoice-table td:nth-child(1){
                    text-align:left;
                    word-break: break-word; /* KLJUČNO */
                }

                .invoice-table {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                }

                .invoice-table th:not(:first-child),
                .invoice-table td:not(:first-child){
                    border-left: 1px solid black;
                }

                .invoice-table th:first-child,
                .invoice-table td:first-child {
                    text-align: left !important;
                }






                .item-row{
                display:flex;
                align-items:center;
                gap:10px;
                width:100%;
                margin-bottom:8px;
                }

                .item-name{
                flex:1;
                }

                .item-qty{
                width:125px;
                flex-shrink:0;
                text-align:center;
                }

                .item-price{
                width:120px;
                flex-shrink:0;
                text-align:center;
                }

                .item-total{
                width:115px;
                flex-shrink:0;
                text-align:right;
                font-weight:600;
                padding-right:10px;	
                }

                .remove-item{
                width:34px;
                height:34px;
                border:none;
                border-radius:6px;
                background:#e74c3c;
                color:white;
                font-weight:bold;
                cursor:pointer;
                transition:0.2s;
                }

                .remove-item:hover{
                background:#c0392b;
                }

                .qty-group{
                display:flex;
                width:175px;
                }

                .item-qty{
                width:110px;
                text-align:center;
                }

                .item-unit{
                width:70px;
                border-left:0;
                }

                /* uklanja strelice u Chrome, Edge, Safari */
                .item-qty::-webkit-inner-spin-button,
                .item-qty::-webkit-outer-spin-button{
                    -webkit-appearance: none;
                    margin: 0;
                }

                /* uklanja strelice u Firefox */
                .item-qty{
                    -moz-appearance: textfield;
                }

                #reverse_vat_note {
                    width: 100%;
                    margin-top: 50px; /* 50px ispod tabele */
                    font-size: 12px;
                    color: #666;
                    text-align: center;
                }

                .invoice-footer {
                    text-align:center;
                    position: fixed;
                    bottom: 5mm;
                    left: 0;
                    right: 0;
                }

                .form-control {
                    color: black !important;
                }

                .card {
                    height: auto !important;
                }
                .card-body {
                    padding: 5px !important;
                }

                .doc-card{
                    border:1px solid #ddd;
                    border-radius:8px;
                    padding:12px;
                    text-align:center;
                    cursor:pointer;
                    transition:all .2s;
                    background:#fff;
                }

                .doc-card:hover{
                    border-color:#0d6efd;
                }

                .doc-card.active{
                    border:2px solid #0d6efd;
                    background:#f3f7ff;
                    color:#0d6efd;
                }

                .a4-preview{
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }

                *{
                    -webkit-font-smoothing: none;
                    text-rendering: geometricPrecision;
                }

	        </style>
        </head>
        <body>
            $html
        </body>
        </html>
        ";

        return Browsershot::html($fullHtml)
                ->noSandbox()
                ->format('A4')
                ->showBackground()
                ->pdf();
    }
}