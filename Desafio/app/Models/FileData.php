<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileData extends Model
{
    use HasFactory;

    protected $fillable = [
        'upload_id',
        'RptDt',
        'TckrSymb',
        'MktNm',
        'SctyCtgyNm',
        'ISIN',
        'CrpnNm',
    ];
}



