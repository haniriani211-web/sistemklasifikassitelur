<?php

namespace Database\Seeders;

use App\Models\DatasetTelur;
use App\Models\KlasifikasiTelur;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Users
        $admin = User::updateOrCreate(
            ['email' => 'admin@rajadesa.com'],
            [
                'name' => 'Administrasi Rajadesa',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $pekerja = User::updateOrCreate(
            ['email' => 'pekerja@rajadesa.com'],
            [
                'name' => 'Pekerja Kandang',
                'password' => Hash::make('password'),
                'role' => 'pekerja_kandang',
            ]
        );

        // Seed Dataset 20 sampel telur dari RUMUS C4.5.xlsx
        $dataset = [
            ['kode_telur' => 'TLR-20260421-001', 'berat' => 62.5, 'diameter' => 4.3, 'kondisi_cangkang' => 'Normal', 'warna_cangkang' => 'Cokelat Tua', 'kualitas' => 'Layak Jual'],
            ['kode_telur' => 'TLR-20260421-002', 'berat' => 64.0, 'diameter' => 4.5, 'kondisi_cangkang' => 'Normal', 'warna_cangkang' => 'Cokelat Tua', 'kualitas' => 'Layak Jual'],
            ['kode_telur' => 'TLR-20260421-003', 'berat' => 58.0, 'diameter' => 4.1, 'kondisi_cangkang' => 'Normal', 'warna_cangkang' => 'Cokelat Tua', 'kualitas' => 'Layak Jual'],
            ['kode_telur' => 'TLR-20260421-004', 'berat' => 60.2, 'diameter' => 4.2, 'kondisi_cangkang' => 'Normal', 'warna_cangkang' => 'Cokelat Muda', 'kualitas' => 'Layak Jual'],
            ['kode_telur' => 'TLR-20260421-005', 'berat' => 61.5, 'diameter' => 4.3, 'kondisi_cangkang' => 'Normal', 'warna_cangkang' => 'Cokelat Tua', 'kualitas' => 'Layak Jual'],
            ['kode_telur' => 'TLR-20260421-006', 'berat' => 59.0, 'diameter' => 4.0, 'kondisi_cangkang' => 'Normal', 'warna_cangkang' => 'Cokelat Muda', 'kualitas' => 'Layak Jual'],
            ['kode_telur' => 'TLR-20260421-007', 'berat' => 63.1, 'diameter' => 4.4, 'kondisi_cangkang' => 'Normal', 'warna_cangkang' => 'Cokelat Tua', 'kualitas' => 'Layak Jual'],
            ['kode_telur' => 'TLR-20260421-008', 'berat' => 65.2, 'diameter' => 4.6, 'kondisi_cangkang' => 'Normal', 'warna_cangkang' => 'Cokelat Tua', 'kualitas' => 'Layak Jual'],
            ['kode_telur' => 'TLR-20260421-009', 'berat' => 57.5, 'diameter' => 4.1, 'kondisi_cangkang' => 'Normal', 'warna_cangkang' => 'Cokelat Muda', 'kualitas' => 'Layak Jual'],
            ['kode_telur' => 'TLR-20260421-010', 'berat' => 56.0, 'diameter' => 3.9, 'kondisi_cangkang' => 'Normal', 'warna_cangkang' => 'Cokelat Muda', 'kualitas' => 'Layak Jual'],
            ['kode_telur' => 'TLR-20260421-011', 'berat' => 60.0, 'diameter' => 4.2, 'kondisi_cangkang' => 'Normal', 'warna_cangkang' => 'Cokelat Tua', 'kualitas' => 'Layak Jual'],
            ['kode_telur' => 'TLR-20260421-012', 'berat' => 45.2, 'diameter' => 3.5, 'kondisi_cangkang' => 'Normal', 'warna_cangkang' => 'Cokelat Muda', 'kualitas' => 'Tidak Layak Jual'],
            ['kode_telur' => 'TLR-20260421-013', 'berat' => 55.0, 'diameter' => 3.8, 'kondisi_cangkang' => 'Retak', 'warna_cangkang' => 'Cokelat Muda', 'kualitas' => 'Layak Jual'],
            ['kode_telur' => 'TLR-20260421-014', 'berat' => 58.5, 'diameter' => 4.0, 'kondisi_cangkang' => 'Retak', 'warna_cangkang' => 'Cokelat Tua', 'kualitas' => 'Layak Jual'],
            ['kode_telur' => 'TLR-20260421-015', 'berat' => 56.2, 'diameter' => 3.9, 'kondisi_cangkang' => 'Retak', 'warna_cangkang' => 'Cokelat Muda', 'kualitas' => 'Layak Jual'],
            ['kode_telur' => 'TLR-20260421-016', 'berat' => 48.0, 'diameter' => 3.6, 'kondisi_cangkang' => 'Retak', 'warna_cangkang' => 'Cokelat Muda', 'kualitas' => 'Tidak Layak Jual'],
            ['kode_telur' => 'TLR-20260421-017', 'berat' => 49.5, 'diameter' => 3.7, 'kondisi_cangkang' => 'Retak', 'warna_cangkang' => 'Cokelat Tua', 'kualitas' => 'Tidak Layak Jual'],
            ['kode_telur' => 'TLR-20260421-018', 'berat' => 51.0, 'diameter' => 3.8, 'kondisi_cangkang' => 'Retak', 'warna_cangkang' => 'Cokelat Muda', 'kualitas' => 'Tidak Layak Jual'],
            ['kode_telur' => 'TLR-20260421-019', 'berat' => 46.8, 'diameter' => 3.5, 'kondisi_cangkang' => 'Retak', 'warna_cangkang' => 'Cokelat Muda', 'kualitas' => 'Tidak Layak Jual'],
            ['kode_telur' => 'TLR-20260421-020', 'berat' => 50.2, 'diameter' => 3.7, 'kondisi_cangkang' => 'Retak', 'warna_cangkang' => 'Cokelat Tua', 'kualitas' => 'Tidak Layak Jual'],
        ];

        foreach ($dataset as $row) {
            DatasetTelur::updateOrCreate(['kode_telur' => $row['kode_telur']], $row);
        }

        // Seed initial klasifikasi telur (sample panen)
        $samplePanen = [
            [
                'kode_telur' => 'PN-20260815-001',
                'tanggal_panen' => now()->toDateString(),
                'berat' => 61.0,
                'diameter' => 4.2,
                'kondisi_cangkang' => 'Normal',
                'warna_cangkang' => 'Cokelat Tua',
                'hasil_klasifikasi' => 'Layak Jual',
                'rule_applied' => 'IF Berat > 53.0 Gram THEN Layak Jual',
                'pekerja_id' => $pekerja->id,
                'catatan' => 'Panen Pagi Blok A',
            ],
            [
                'kode_telur' => 'PN-20260815-002',
                'tanggal_panen' => now()->toDateString(),
                'berat' => 47.5,
                'diameter' => 3.5,
                'kondisi_cangkang' => 'Retak',
                'warna_cangkang' => 'Cokelat Muda',
                'hasil_klasifikasi' => 'Tidak Layak Jual',
                'rule_applied' => 'IF Berat <= 53.0 Gram THEN Tidak Layak Jual',
                'pekerja_id' => $pekerja->id,
                'catatan' => 'Telur Retak & Kecil',
            ],
        ];

        foreach ($samplePanen as $panen) {
            KlasifikasiTelur::updateOrCreate(['kode_telur' => $panen['kode_telur']], $panen);
        }
    }
}
