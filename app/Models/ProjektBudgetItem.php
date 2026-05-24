<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjektBudgetItem extends Model
{
    use SoftDeletes;

    protected $table = 'projekt_budget_items';

    protected $fillable = [
        'projekt_id',
        'projekt_record_id',
        'type',
        'category',
        'description',
        'amount',
        'entry_date',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'entry_date' => 'date',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    public function projekt()
    {
        return $this->belongsTo(Projekt::class, 'projekt_id');
    }

    public function record()
    {
        return $this->belongsTo(ProjektRecord::class, 'projekt_record_id');
    }
}
