<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KlasifikasiTelur extends Model
{
    use HasFactory;

    protected $table = 'klasifikasi_telur';

    protected $fillable = [
        'kode_telur',
        'tanggal_panen',
        'berat',
        'diameter',
        'kondisi_cangkang',
        'warna_cangkang',
        'hasil_klasifikasi',
        'rule_applied',
        'pekerja_id',
        'catatan',
    ];

    public function pekerja(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pekerja_id');
    }
}
