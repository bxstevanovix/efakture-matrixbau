<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjektDocument extends Model
{
    use SoftDeletes;

    protected $table = 'projekt_documents';

    protected $fillable = [
        'projekt_id',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    public function projekt()
    {
        return $this->belongsTo(Projekt::class, 'projekt_id');
    }
}
