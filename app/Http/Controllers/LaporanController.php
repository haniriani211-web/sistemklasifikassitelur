<?php

namespace App\Http\Controllers;

use App\Models\KlasifikasiTelur;
use App\Models\User;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30)->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $hasil = $request->input('hasil');
        $pekerjaId = $request->input('pekerja_id');

        $query = KlasifikasiTelur::with('pekerja')
            ->whereBetween('tanggal_panen', [$startDate, $endDate]);

        if ($hasil) {
            $query->where('hasil_klasifikasi', $hasil);
        }

        if ($pekerjaId) {
            $query->where('pekerja_id', $pekerjaId);
        }

        $items = $query->latest('tanggal_panen')->get();

        $total = $items->count();
        $totalLayak = $items->where('hasil_klasifikasi', 'Layak Jual')->count();
        $totalTidakLayak = $items->where('hasil_klasifikasi', 'Tidak Layak Jual')->count();
        $pctLayak = $total > 0 ? round(($totalLayak / $total) * 100, 1) : 0;
        $pctTidakLayak = $total > 0 ? round(($totalTidakLayak / $total) * 100, 1) : 0;

        $pekerjas = User::where('role', 'pekerja_kandang')->get();

        return view('laporan.index', compact(
            'items',
            'startDate',
            'endDate',
            'hasil',
            'pekerjaId',
            'total',
            'totalLayak',
            'totalTidakLayak',
            'pctLayak',
            'pctTidakLayak',
            'pekerjas'
        ));
    }

    public function cetak(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30)->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $hasil = $request->input('hasil');
        $pekerjaId = $request->input('pekerja_id');

        $query = KlasifikasiTelur::with('pekerja')
            ->whereBetween('tanggal_panen', [$startDate, $endDate]);

        if ($hasil) {
            $query->where('hasil_klasifikasi', $hasil);
        }

        if ($pekerjaId) {
            $query->where('pekerja_id', $pekerjaId);
        }

        $items = $query->orderBy('tanggal_panen', 'asc')->get();

        $total = $items->count();
        $totalLayak = $items->where('hasil_klasifikasi', 'Layak Jual')->count();
        $totalTidakLayak = $items->where('hasil_klasifikasi', 'Tidak Layak Jual')->count();

        $pekerjaSelected = $pekerjaId ? User::find($pekerjaId) : null;

        return view('laporan.cetak', compact(
            'items',
            'startDate',
            'endDate',
            'hasil',
            'total',
            'totalLayak',
            'totalTidakLayak',
            'pekerjaSelected'
        ));
    }
}
