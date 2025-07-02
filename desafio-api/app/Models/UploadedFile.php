<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class UploadedFile extends Model
{
    protected $table = 'uploaded_files';

    protected $fillable = [
        'filename',
        'hash',
        'path',
        'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public $timestamps = true;

    public function instrumentRecords(): HasMany
    {
        return $this->hasMany(InstrumentRecord::class, 'uploaded_file_id', 'id');
    }
}