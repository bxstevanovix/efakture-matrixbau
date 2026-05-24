<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjektRecord extends Model
{
    use SoftDeletes;

    protected $table = 'projekt_records';

    protected $fillable = [
        'projekt_id',
        'title',
        'description',
        'report',
        'position',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'position' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    public function projekt()
    {
        return $this->belongsTo(Projekt::class, 'projekt_id');
    }

    public function files()
    {
        return $this->hasMany(ProjektRecordFile::class, 'projekt_record_id');
    }

    public function budgetItems()
    {
        return $this->hasMany(ProjektBudgetItem::class, 'projekt_record_id');
    }
}
