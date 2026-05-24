<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjektRecordFile extends Model
{
    use SoftDeletes;

    protected $table = 'projekt_record_files';

    protected $fillable = [
        'projekt_record_id',
        'file_type',
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

    public function record()
    {
        return $this->belongsTo(ProjektRecord::class, 'projekt_record_id');
    }
}
