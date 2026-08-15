<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatasetTelur extends Model
{
    use HasFactory;

    protected $table = 'dataset_telur';

    protected $fillable = [
        'kode_telur',
        'berat',
        'diameter',
        'kondisi_cangkang',
        'warna_cangkang',
        'kualitas',
    ];
}
