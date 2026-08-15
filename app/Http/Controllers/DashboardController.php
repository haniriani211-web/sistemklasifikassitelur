<?php

namespace App\Http\Controllers;

use App\Models\DatasetTelur;
use App\Models\KlasifikasiTelur;
use App\Services\C45Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(C45Service $c45Service)
    {
        $totalPanen = KlasifikasiTelur::count();
        $totalLayak = KlasifikasiTelur::where('hasil_klasifikasi', 'Layak Jual')->count();
        $totalTidakLayak = KlasifikasiTelur::where('hasil_klasifikasi', 'Tidak Layak Jual')->count();
        $layakPercentage = $totalPanen > 0 ? round(($totalLayak / $totalPanen) * 100, 1) : 0;

        $totalDataset = DatasetTelur::count();

        // Recent Panen Records
        $recentPanen = KlasifikasiTelur::with('pekerja')
            ->latest()
            ->take(7)
            ->get();

        // C45 Model Stats & Evaluation
        $dataset = DatasetTelur::all();
        $c45Stats = $c45Service->getCalculationDetails($dataset);

        // Fetch db panen counts grouped by date
        $dbCounts = KlasifikasiTelur::select(
                DB::raw('DATE(tanggal_panen) as date'),
                DB::raw("SUM(CASE WHEN hasil_klasifikasi = 'Layak Jual' THEN 1 ELSE 0 END) as layak"),
                DB::raw("SUM(CASE WHEN hasil_klasifikasi = 'Tidak Layak Jual' THEN 1 ELSE 0 END) as tidak_layak")
            )
            ->whereDate('tanggal_panen', '>=', now()->subDays(6)->toDateString())
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        // Build 7-day series guaranteed
        $chartPanen = [];
        for ($i = 6; $i >= 0; $i--) {
            $dt = now()->subDays($i)->format('Y-m-d');
            $dateLabel = now()->subDays($i)->format('d M');
            $layak = isset($dbCounts[$dt]) ? (int) $dbCounts[$dt]->layak : 0;
            $tidakLayak = isset($dbCounts[$dt]) ? (int) $dbCounts[$dt]->tidak_layak : 0;

            $chartPanen[] = [
                'date' => $dateLabel,
                'full_date' => $dt,
                'layak' => $layak,
                'tidak_layak' => $tidakLayak,
                'total' => $layak + $tidakLayak,
            ];
        }

        return view('dashboard', compact(
            'totalPanen',
            'totalLayak',
            'totalTidakLayak',
            'layakPercentage',
            'totalDataset',
            'recentPanen',
            'c45Stats',
            'chartPanen'
        ));
    }
}
