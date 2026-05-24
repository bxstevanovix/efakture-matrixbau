<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Projekt;
use App\Models\ProjektBudgetItem;
use App\Models\ProjektDocument;
use App\Models\ProjektRecord;
use App\Models\ProjektRecordFile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ProjektiController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $inProgressProjects = Projekt::whereNotIn('status', ['completed', 'cancelled'])
            ->whereNotNull('start_date')
            ->whereDate('start_date', '<=', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $allProjects = Projekt::orderBy('created_at', 'desc')->get();
        $userIds = $allProjects
            ->pluck('created_by')
            ->merge($allProjects->pluck('updated_by'))
            ->filter()
            ->unique()
            ->values();

        $usersById = User::whereIn('id', $userIds)->pluck('name', 'id');

        return view('projekti.index', [
            'inProgressProjects' => $inProgressProjects,
            'allProjects' => $allProjects,
            'usersById' => $usersById,
        ]);
    }

    /**
     * Datatable za sve projekte
     */
    public function datatable()
    {
        $query = Projekt::query()
            ->where('id', '>', 0)
            ->orderBy('created_at', 'desc');

        return datatables($query)
            ->addColumn('actions', function ($entity) {
                return view('projekti.partials.table.actions', ['entity' => $entity]);
            })
            ->editColumn('status', function ($entity) {
                return '<span class="badge ' . $entity->timeline_status_badge . ' light border-0">' . e($entity->timeline_status_label) . '</span>';
            })
            ->editColumn('start_date', function ($entity) {
                return $entity->start_date?->format('d.m.Y') ?? '-';
            })
            ->editColumn('end_date', function ($entity) {
                return $entity->end_date?->format('d.m.Y') ?? '-';
            })
            ->addColumn('progress', function ($entity) {
                $progress = $entity->timeline_progress;
                $progressClass = match(true) {
                    $progress >= 75 => 'bg-success',
                    $progress >= 50 => 'bg-info',
                    $progress >= 25 => 'bg-warning',
                    default => 'bg-danger'
                };

                return '<div class="progress" style="height: 20px;">
                    <div class="progress-bar ' . $progressClass . '" 
                         style="width:' . $progress . '%; border-radius: 4px;" 
                         role="progressbar">
                        <span class="ms-2" style="color: white; font-size: 12px; font-weight: 500;">' . $progress . '%</span>
                    </div>
                </div>';
            })
            ->rawColumns(['actions', 'status', 'progress'])
            ->setRowAttr([
                'data-id' => function($entity) {
                    return $entity->id;
                }
            ])
            ->make(true);
    }

    public function save()
    {
        $data = $this->request->validate([
            'project_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'address' => ['nullable', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['nullable', 'in:open,in_progress,completed,cancelled'],
            'planned_budget' => ['nullable', 'numeric', 'min:0', 'max:999999999.99'],
        ]);

        $data['status'] = $data['status'] ?? 'open';

        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        $projekt = Projekt::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Projekt je uspešno sačuvan!',
            'data' => $projekt
        ]);
    }

    public function show(Projekt $entity)
    {
        $progressBars = [
            'open' => 'bg-warning',
            'in_progress' => 'bg-info',
            'completed' => 'bg-success',
            'cancelled' => 'bg-danger',
        ];

        $periods = [];

        if (Schema::hasTable('projekt_records')) {
            $recordsQuery = $entity->records()
                ->orderBy('position')
                ->orderBy('created_at');

            if (Schema::hasTable('projekt_record_files')) {
                $recordsQuery->with('files');
            }

            if (Schema::hasTable('projekt_budget_items')) {
                $recordsQuery->with([
                    'budgetItems' => fn ($query) => $query
                        ->orderByRaw('entry_date IS NULL')
                        ->orderByDesc('entry_date')
                        ->orderByDesc('created_at'),
                ]);
            }

            $periods = $recordsQuery->get()
                ->map(fn (ProjektRecord $record) => $this->formatProjectRecord($record))
                ->all();
        }

        $projectLead = User::find($entity->created_by)?->name ?? 'Administrator';
        $assignee = User::find($entity->updated_by)?->name ?? $projectLead;
        $projectAddress = $entity->address;
        $budgetSummary = $this->projectBudgetSummary($entity);
        $projectDocumentsAvailable = Schema::hasTable('projekt_documents');
        $projectDocuments = [];

        if ($projectDocumentsAvailable) {
            $projectDocuments = $entity->documents()
                ->orderByDesc('created_at')
                ->get()
                ->map(fn (ProjektDocument $document) => $this->formatProjectDocument($document))
                ->all();
        }

        return view('projekti.show', [
            'projekt' => $entity,
            'periods' => $periods,
            'projectLead' => $projectLead,
            'assignee' => $assignee,
            'projectAddress' => $projectAddress,
            'statusLabel' => $entity->timeline_status_label,
            'badgeClass' => $entity->timeline_status_badge,
            'progressClass' => $progressBars[$entity->timeline_status] ?? 'bg-primary',
            'budgetSummary' => $budgetSummary,
            'budgetCategories' => $this->projectBudgetCategories(),
            'projectDocumentsAvailable' => $projectDocumentsAvailable,
            'projectDocuments' => $projectDocuments,
        ]);
    }

    public function update(Projekt $entity)
    {
        $data = $this->request->validate([
            'project_name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'address' => ['sometimes', 'nullable', 'string', 'max:255'],
            'start_date' => ['sometimes', 'required', 'date'],
            'end_date' => ['sometimes', 'nullable', 'date'],
            'status' => ['sometimes', 'required', 'in:open,in_progress,completed,cancelled'],
            'planned_budget' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:999999999.99'],
        ]);

        $startDate = array_key_exists('start_date', $data) ? $data['start_date'] : $entity->start_date;

        if (!empty($data['end_date']) && $startDate && Carbon::parse($data['end_date'])->lt(Carbon::parse($startDate))) {
            return response()->json([
                'message' => 'Datum završetka ne može biti prije datuma početka.',
                'errors' => [
                    'end_date' => ['Datum završetka ne može biti prije datuma početka.'],
                ],
            ], 422);
        }

        foreach (['project_name', 'description', 'start_date', 'end_date', 'status'] as $field) {
            if (array_key_exists($field, $data)) {
                $entity->{$field} = $data[$field];
            }
        }

        if (array_key_exists('planned_budget', $data)) {
            $entity->planned_budget = $data['planned_budget'];
        }

        if (array_key_exists('address', $data)) {
            $entity->address = $data['address'];
        }

        $entity->updated_by = Auth::id();
        $entity->save();

        return response()->json([
            'success' => true,
            'message' => 'Projekt je uspešno ažuriran!',
            'data' => $entity,
        ]);
    }

    public function delete(Projekt $entity)
    {
        $entity->delete();

        return response()->json([
            'success' => true,
            'message' => 'Projekt je uspešno obrisan!'
        ]);
    }

    public function storeRecord(Projekt $entity)
    {
        if (!Schema::hasTable('projekt_records')) {
            return $this->missingProjectRecordsTableResponse();
        }

        $data = $this->request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'report' => ['nullable', 'string'],
        ]);

        $minPosition = $entity->records()->min('position');

        $record = $entity->records()->create([
            'title' => ($data['title'] ?? null) ?: 'Novi zapis ' . now()->format('d.m.Y'),
            'description' => $data['description'] ?? null,
            'report' => $data['report'] ?? null,
            'position' => is_null($minPosition) ? 0 : ((int) $minPosition - 1),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Zapis je sačuvan.',
            'record' => $this->formatProjectRecord($record),
        ]);
    }

    public function updateRecord(Projekt $entity, ProjektRecord $record)
    {
        if (!Schema::hasTable('projekt_records')) {
            return $this->missingProjectRecordsTableResponse();
        }

        $this->ensureRecordBelongsToProject($entity, $record);

        $data = $this->request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'report' => ['sometimes', 'nullable', 'string'],
        ]);

        foreach (['title', 'description', 'report'] as $field) {
            if (array_key_exists($field, $data)) {
                $record->{$field} = $data[$field];
            }
        }

        $record->updated_by = Auth::id();
        $record->save();

        return response()->json([
            'success' => true,
            'message' => 'Zapis je ažuriran.',
            'record' => $this->formatProjectRecord($record),
        ]);
    }

    public function deleteRecord(Projekt $entity, ProjektRecord $record)
    {
        if (!Schema::hasTable('projekt_records')) {
            return $this->missingProjectRecordsTableResponse();
        }

        $this->ensureRecordBelongsToProject($entity, $record);

        if (Schema::hasTable('projekt_budget_items')) {
            $record->budgetItems()->delete();
        }

        $record->delete();

        return response()->json([
            'success' => true,
            'message' => 'Zapis je obrisan.',
            'summary' => $this->projectBudgetSummary($entity),
        ]);
    }

    public function uploadRecordFile(Projekt $entity, ProjektRecord $record)
    {
        if (!Schema::hasTable('projekt_records')) {
            return $this->missingProjectRecordsTableResponse();
        }

        if (!Schema::hasTable('projekt_record_files')) {
            return $this->missingProjectRecordFilesTableResponse();
        }

        $this->ensureRecordBelongsToProject($entity, $record);

        $data = $this->request->validate([
            'file_type' => ['required', 'in:document,invoice'],
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg,txt,csv,zip', 'max:102400'],
        ]);

        $uploadedFile = $data['file'];
        $originalName = $uploadedFile->getClientOriginalName();
        $extension = $uploadedFile->getClientOriginalExtension();
        $nameWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);
        $cleanName = Str::slug($nameWithoutExtension) ?: 'fajl';
        $fileName = $cleanName . '-' . now()->format('YmdHis') . '-' . Str::random(6) . ($extension ? '.' . $extension : '');
        $folderType = $data['file_type'] === 'invoice' ? 'racuni' : 'dokumenti';
        $folder = 'projekti/' . $entity->id . '/zapisi/' . $record->id . '/' . $folderType;
        $path = $uploadedFile->storeAs($folder, $fileName, 'local');

        $recordFile = $record->files()->create([
            'file_type' => $data['file_type'],
            'file_name' => $originalName,
            'file_path' => $path,
            'mime_type' => $uploadedFile->getClientMimeType(),
            'file_size' => $uploadedFile->getSize() ?: 0,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Fajl je uspešno dodat.',
            'file' => $this->formatProjectRecordFile($recordFile, $record),
            'counts' => $this->projectRecordFileCounts($record),
        ]);
    }

    public function viewRecordFile(Projekt $entity, ProjektRecord $record, ProjektRecordFile $file)
    {
        if (!Schema::hasTable('projekt_records') || !Schema::hasTable('projekt_record_files')) {
            abort(404);
        }

        $this->ensureRecordBelongsToProject($entity, $record);
        $this->ensureFileBelongsToRecord($record, $file);
        $this->protectProjectRecordFileStorage($file);

        abort_unless($file->file_path && Storage::disk('local')->exists($file->file_path), 404);

        $mimeType = $file->mime_type ?: Storage::disk('local')->mimeType($file->file_path);

        return Storage::disk('local')->response($file->file_path, $file->file_name, [
            'Content-Type' => $mimeType,
        ]);
    }

    public function deleteRecordFile(Projekt $entity, ProjektRecord $record, ProjektRecordFile $file)
    {
        if (!Schema::hasTable('projekt_records')) {
            return $this->missingProjectRecordsTableResponse();
        }

        if (!Schema::hasTable('projekt_record_files')) {
            return $this->missingProjectRecordFilesTableResponse();
        }

        $this->ensureRecordBelongsToProject($entity, $record);
        $this->ensureFileBelongsToRecord($record, $file);

        if ($file->file_path && Storage::disk('local')->exists($file->file_path)) {
            Storage::disk('local')->delete($file->file_path);
        }

        if ($file->file_path && Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }

        $file->delete();

        return response()->json([
            'success' => true,
            'message' => 'Fajl je obrisan.',
            'counts' => $this->projectRecordFileCounts($record),
        ]);
    }

    public function uploadDocument(Projekt $entity)
    {
        if (!Schema::hasTable('projekt_documents')) {
            return $this->missingProjectDocumentsTableResponse();
        }

        $data = $this->request->validate([
            'documents' => ['required', 'array', 'min:1', 'max:10'],
            'documents.*' => ['required', 'file', 'mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg,txt,csv,zip', 'max:102400'],
        ]);

        $documents = collect();

        foreach ($data['documents'] as $uploadedFile) {
            $originalName = $uploadedFile->getClientOriginalName();
            $extension = $uploadedFile->getClientOriginalExtension();
            $nameWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);
            $cleanName = Str::slug($nameWithoutExtension) ?: 'dokument';
            $fileName = $cleanName . '-' . now()->format('YmdHis') . '-' . Str::random(6) . ($extension ? '.' . $extension : '');
            $folder = 'projekti/' . $entity->id . '/dokumentacija';
            $path = $uploadedFile->storeAs($folder, $fileName, 'local');

            $documents->push($entity->documents()->create([
                'file_name' => $originalName,
                'file_path' => $path,
                'mime_type' => $uploadedFile->getClientMimeType(),
                'file_size' => $uploadedFile->getSize() ?: 0,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]));
        }

        return response()->json([
            'success' => true,
            'message' => 'Dokumentacija je uspešno dodata.',
            'documents' => $documents
                ->map(fn (ProjektDocument $document) => $this->formatProjectDocument($document))
                ->values()
                ->all(),
            'count' => $this->projectDocumentCount($entity),
        ]);
    }

    public function viewDocument(Projekt $entity, ProjektDocument $document)
    {
        if (!Schema::hasTable('projekt_documents')) {
            abort(404);
        }

        $this->ensureDocumentBelongsToProject($entity, $document);
        $this->protectProjectDocumentStorage($document);

        abort_unless($document->file_path && Storage::disk('local')->exists($document->file_path), 404);

        $mimeType = $document->mime_type ?: Storage::disk('local')->mimeType($document->file_path);

        return Storage::disk('local')->response($document->file_path, $document->file_name, [
            'Content-Type' => $mimeType,
        ]);
    }

    public function deleteDocument(Projekt $entity, ProjektDocument $document)
    {
        if (!Schema::hasTable('projekt_documents')) {
            return $this->missingProjectDocumentsTableResponse();
        }

        $this->ensureDocumentBelongsToProject($entity, $document);

        if ($document->file_path && Storage::disk('local')->exists($document->file_path)) {
            Storage::disk('local')->delete($document->file_path);
        }

        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Dokument je obrisan.',
            'count' => $this->projectDocumentCount($entity),
        ]);
    }

    public function storeBudgetItem(Projekt $entity)
    {
        if (!Schema::hasTable('projekt_budget_items')) {
            return $this->missingProjectBudgetTableResponse();
        }

        $data = $this->request->validate([
            'type' => ['required', 'in:income,expense'],
            'category' => ['nullable', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:999999999.99'],
            'entry_date' => ['nullable', 'date'],
            'projekt_record_id' => ['required', 'integer'],
        ]);

        $recordId = $data['projekt_record_id'] ?? null;

        if ($recordId) {
            if (!Schema::hasTable('projekt_records') || !$entity->records()->whereKey($recordId)->exists()) {
                return response()->json([
                    'message' => 'Izabrani zapis ne pripada ovom projektu.',
                    'errors' => [
                        'projekt_record_id' => ['Izabrani zapis ne pripada ovom projektu.'],
                    ],
                ], 422);
            }
        }

        $budgetItem = $entity->budgetItems()->create([
            'projekt_record_id' => $recordId,
            'type' => $data['type'],
            'category' => $data['category'] ?? null,
            'description' => $data['description'] ?? null,
            'amount' => $data['amount'],
            'entry_date' => $data['entry_date'] ?? null,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        if (Schema::hasTable('projekt_records')) {
            $budgetItem->load('record');
        }

        return response()->json([
            'success' => true,
            'message' => 'Stavka budžeta je dodata.',
            'item' => $this->formatProjectBudgetItem($budgetItem),
            'summary' => $this->projectBudgetSummary($entity),
        ]);
    }

    public function deleteBudgetItem(Projekt $entity, ProjektBudgetItem $budgetItem)
    {
        if (!Schema::hasTable('projekt_budget_items')) {
            return $this->missingProjectBudgetTableResponse();
        }

        $this->ensureBudgetItemBelongsToProject($entity, $budgetItem);

        $budgetItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Stavka budžeta je obrisana.',
            'summary' => $this->projectBudgetSummary($entity),
        ]);
    }

    public function viewPdf($id)
    {
        // Za budućnost ako trebamo PDF prikaz
        $projekt = Projekt::findOrFail($id);
        return view('projekti.view', ['projekt' => $projekt]);
    }

    public function autocompleteFirma()
    {
        $search = $this->request->input('q', '');
        // Za budućnost ako trebamo autocomplete
        return response()->json([]);
    }

    public function autocompleteAdress()
    {
        $search = $this->request->input('q', '');
        // Za budućnost ako trebamo autocomplete
        return response()->json([]);
    }

    private function formatProjectRecord(ProjektRecord $record): array
    {
        $files = collect();
        $budgetItems = collect();

        if (Schema::hasTable('projekt_record_files')) {
            $files = $record->relationLoaded('files')
                ? $record->files
                : $record->files()->orderBy('created_at')->get();
        }

        if (Schema::hasTable('projekt_budget_items')) {
            $budgetItems = $record->relationLoaded('budgetItems')
                ? $record->budgetItems
                : $record->budgetItems()
                    ->orderByRaw('entry_date IS NULL')
                    ->orderByDesc('entry_date')
                    ->orderByDesc('created_at')
                    ->get();
        }

        $documents = $files->where('file_type', 'document');
        $invoices = $files->where('file_type', 'invoice');
        $budgetIncome = (float) $budgetItems->where('type', 'income')->sum('amount');
        $budgetExpense = (float) $budgetItems->where('type', 'expense')->sum('amount');

        return [
            'id' => $record->id,
            'key' => 'record' . $record->id,
            'label' => $record->title,
            'description' => $record->description ?? '',
            'report' => $record->report ?? '',
            'documents' => $documents
                ->map(fn (ProjektRecordFile $file) => $this->formatProjectRecordFile($file, $record))
                ->values()
                ->all(),
            'invoices' => $invoices
                ->map(fn (ProjektRecordFile $file) => $this->formatProjectRecordFile($file, $record))
                ->values()
                ->all(),
            'file_count' => $documents->count() + $invoices->count(),
            'budget_income' => $budgetIncome,
            'budget_expense' => $budgetExpense,
            'budget_income_formatted' => $this->formatMoney($budgetIncome),
            'budget_expense_formatted' => $this->formatMoney($budgetExpense),
            'budget_items' => $budgetItems
                ->map(fn (ProjektBudgetItem $item) => $this->formatProjectBudgetItem($item))
                ->values()
                ->all(),
        ];
    }

    private function formatProjectRecordFile(ProjektRecordFile $file, ?ProjektRecord $record = null): array
    {
        $record = $record ?: $file->record;

        if ($record) {
            $this->protectProjectRecordFileStorage($file);
        }

        return [
            'id' => $file->id,
            'name' => $file->file_name,
            'url' => $record
                ? route('projekti.records.files.view', [$record->projekt_id, $record->id, $file->id])
                : '#',
            'size' => $this->formatFileSize((int) $file->file_size),
            'type' => $file->file_type,
        ];
    }

    private function formatProjectDocument(ProjektDocument $document): array
    {
        $this->protectProjectDocumentStorage($document);

        return [
            'id' => $document->id,
            'name' => $document->file_name,
            'url' => route('projekti.documents.view', [$document->projekt_id, $document->id]),
            'size' => $this->formatFileSize((int) $document->file_size),
        ];
    }

    private function projectBudgetSummary(Projekt $projekt): array
    {
        $hasBudgetItems = Schema::hasTable('projekt_budget_items');
        $planned = (float) ($projekt->planned_budget ?? 0);
        $income = 0.0;
        $expense = 0.0;

        if ($hasBudgetItems) {
            $income = (float) $projekt->budgetItems()->where('type', 'income')->sum('amount');
            $expense = (float) $projekt->budgetItems()->where('type', 'expense')->sum('amount');
        }

        $balance = $income - $expense;
        $remaining = $planned > 0 ? $planned - $expense : $balance;
        $expenseUsage = $planned > 0 ? (int) round(($expense / $planned) * 100) : 0;

        return [
            'available' => $hasBudgetItems,
            'planned' => $planned,
            'income' => $income,
            'expense' => $expense,
            'balance' => $balance,
            'remaining' => $remaining,
            'expense_usage' => max(0, min(100, $expenseUsage)),
            'planned_formatted' => $this->formatMoney($planned),
            'income_formatted' => $this->formatMoney($income),
            'expense_formatted' => $this->formatMoney($expense),
            'balance_formatted' => $this->formatMoney($balance),
            'remaining_formatted' => $this->formatMoney($remaining),
        ];
    }

    private function formatProjectBudgetItem(ProjektBudgetItem $item): array
    {
        $categories = $this->projectBudgetCategories();
        $typeLabels = [
            'income' => 'Prihod',
            'expense' => 'Trošak',
        ];

        return [
            'id' => $item->id,
            'type' => $item->type,
            'type_label' => $typeLabels[$item->type] ?? ucfirst($item->type),
            'category' => $item->category,
            'category_label' => $categories[$item->category] ?? ($item->category ?: 'Ostalo'),
            'description' => $item->description ?? '',
            'amount' => (float) $item->amount,
            'amount_formatted' => $this->formatMoney((float) $item->amount),
            'entry_date' => $item->entry_date?->format('Y-m-d'),
            'date_label' => $item->entry_date?->format('d.m.Y') ?? '-',
            'record_id' => $item->projekt_record_id,
            'record_label' => $item->record?->title,
        ];
    }

    private function projectBudgetCategories(): array
    {
        return [
            'material' => 'Materijal',
            'labor' => 'Rad',
            'subcontractor' => 'Podizvođači',
            'transport' => 'Transport',
            'equipment' => 'Oprema',
            'administration' => 'Administracija',
            'other' => 'Ostalo',
        ];
    }

    private function formatMoney(float $amount): string
    {
        return number_format($amount, 2, ',', '.') . ' €';
    }

    private function projectRecordFileCounts(ProjektRecord $record): array
    {
        if (!Schema::hasTable('projekt_record_files')) {
            return [
                'document' => 0,
                'invoice' => 0,
            ];
        }

        return [
            'document' => (int) $record->files()->where('file_type', 'document')->count(),
            'invoice' => (int) $record->files()->where('file_type', 'invoice')->count(),
        ];
    }

    private function projectDocumentCount(Projekt $projekt): int
    {
        if (!Schema::hasTable('projekt_documents')) {
            return 0;
        }

        return (int) $projekt->documents()->count();
    }

    private function formatFileSize(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 1) . ' MB';
        }

        return max(1, round($bytes / 1024)) . ' KB';
    }

    private function ensureRecordBelongsToProject(Projekt $projekt, ProjektRecord $record): void
    {
        abort_unless((int) $record->projekt_id === (int) $projekt->id, 404);
    }

    private function ensureFileBelongsToRecord(ProjektRecord $record, ProjektRecordFile $file): void
    {
        abort_unless((int) $file->projekt_record_id === (int) $record->id, 404);
    }

    private function ensureDocumentBelongsToProject(Projekt $projekt, ProjektDocument $document): void
    {
        abort_unless((int) $document->projekt_id === (int) $projekt->id, 404);
    }

    private function ensureBudgetItemBelongsToProject(Projekt $projekt, ProjektBudgetItem $budgetItem): void
    {
        abort_unless((int) $budgetItem->projekt_id === (int) $projekt->id, 404);
    }

    private function protectProjectDocumentStorage(ProjektDocument $document): void
    {
        if (!$document->file_path || Storage::disk('local')->exists($document->file_path)) {
            return;
        }

        if (!Storage::disk('public')->exists($document->file_path)) {
            return;
        }

        $stream = Storage::disk('public')->readStream($document->file_path);

        if (!$stream) {
            return;
        }

        $stored = Storage::disk('local')->put($document->file_path, $stream);

        if (is_resource($stream)) {
            fclose($stream);
        }

        if ($stored) {
            Storage::disk('public')->delete($document->file_path);
        }
    }

    private function protectProjectRecordFileStorage(ProjektRecordFile $file): void
    {
        if (!$file->file_path || Storage::disk('local')->exists($file->file_path)) {
            return;
        }

        if (!Storage::disk('public')->exists($file->file_path)) {
            return;
        }

        $stream = Storage::disk('public')->readStream($file->file_path);

        if (!$stream) {
            return;
        }

        $stored = Storage::disk('local')->put($file->file_path, $stream);

        if (is_resource($stream)) {
            fclose($stream);
        }

        if ($stored) {
            Storage::disk('public')->delete($file->file_path);
        }
    }

    private function missingProjectRecordsTableResponse()
    {
        return response()->json([
            'success' => false,
            'message' => 'Tabela za zapise projekta nije kreirana. Potrebno je pokrenuti migracije.',
        ], 503);
    }

    private function missingProjectRecordFilesTableResponse()
    {
        return response()->json([
            'success' => false,
            'message' => 'Tabela za fajlove zapisa nije kreirana. Potrebno je pokrenuti migracije.',
        ], 503);
    }

    private function missingProjectDocumentsTableResponse()
    {
        return response()->json([
            'success' => false,
            'message' => 'Tabela za projektnu dokumentaciju nije kreirana. Potrebno je pokrenuti migracije.',
        ], 503);
    }

    private function missingProjectBudgetTableResponse()
    {
        return response()->json([
            'success' => false,
            'message' => 'Tabela za budžet projekta nije kreirana. Potrebno je pokrenuti migracije.',
        ], 503);
    }
}
