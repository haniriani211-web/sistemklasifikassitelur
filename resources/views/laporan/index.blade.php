@extends('layouts.app')

@section('title', 'Rekapitulasi & Laporan Klasifikasi Telur')
@section('page-title', 'Rekapitulasi & Pembuatan Laporan')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">Rekapitulasi Data Klasifikasi Telur</h1>
            <p class="text-xs text-slate-500 mt-0.5">Filter dan cetak laporan resmi hasil pemanenan serta kelayakan kualitas telur untuk administrasi peternakan</p>
        </div>
        <a href="{{ route('laporan.cetak', request()->query()) }}" target="_blank"
            class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs rounded-xl shadow-md transition-all flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-print"></i>
            <span>Cetak Laporan / PDF</span>
        </a>
    </div>

    <!-- Filter Card -->
    <div class="p-5 bg-white rounded-2xl border border-slate-200 shadow-sm">
        <form action="{{ route('laporan.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div>
                <label for="start_date" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Dari Tanggal</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label for="end_date" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Sampai Tanggal</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label for="hasil" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Status Kelayakan</label>
                <select name="hasil" id="hasil" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-amber-500">
                    <option value="">Semua Status</option>
                    <option value="Layak Jual" {{ $hasil === 'Layak Jual' ? 'selected' : '' }}>Layak Jual</option>
                    <option value="Tidak Layak Jual" {{ $hasil === 'Tidak Layak Jual' ? 'selected' : '' }}>Tidak Layak Jual</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="w-full py-2.5 px-4 bg-amber-500 hover:bg-amber-600 text-slate-950 font-extrabold text-xs rounded-xl shadow-md transition-all">
                    <i class="fa-solid fa-filter me-1"></i> Tampilkan Laporan
                </button>
            </div>
        </form>
    </div>

    <!-- Period Recap Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-5 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500">Total Panen Periode Ini</span>
                <h3 class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($total) }} <span class="text-xs font-normal text-slate-500">butir</span></h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xl">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
        </div>

        <div class="p-5 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500">Jumlah Layak Jual</span>
                <h3 class="text-2xl font-extrabold text-emerald-600 mt-1">{{ number_format($totalLayak) }} <span class="text-xs font-bold text-emerald-500">({{ $pctLayak }}%)</span></h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xl">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <div class="p-5 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500">Jumlah Tidak Layak Jual</span>
                <h3 class="text-2xl font-extrabold text-rose-600 mt-1">{{ number_format($totalTidakLayak) }} <span class="text-xs font-bold text-rose-500">({{ $pctTidakLayak }}%)</span></h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-xl">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <h3 class="font-extrabold text-slate-900 text-sm">Tabel Rincian Hasil Klasifikasi Panen Telur</h3>
            <span class="text-xs text-slate-500">Periode: <strong>{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}</strong> s/d <strong>{{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</strong></span>
        </div>

        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-100 text-slate-600 font-semibold uppercase border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Kode Telur</th>
                        <th class="px-4 py-3">Tanggal Panen</th>
                        <th class="px-4 py-3">Berat (g)</th>
                        <th class="px-4 py-3">Diameter (cm)</th>
                        <th class="px-4 py-3">Kondisi Cangkang</th>
                        <th class="px-4 py-3">Warna Cangkang</th>
                        <th class="px-4 py-3">Hasil Klasifikasi C4.5</th>
                        <th class="px-4 py-3">Petugas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 font-medium">
                    @forelse($items as $idx => $row)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 text-slate-400">{{ $idx + 1 }}</td>
                            <td class="px-4 py-3 font-bold text-slate-900">{{ $row->kode_telur }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ \Carbon\Carbon::parse($row->tanggal_panen)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-800">{{ number_format($row->berat, 1) }}</td>
                            <td class="px-4 py-3 text-slate-800">{{ number_format($row->diameter, 1) }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $row->kondisi_cangkang === 'Normal' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $row->kondisi_cangkang }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-700">{{ $row->warna_cangkang }}</td>
                            <td class="px-4 py-3">
                                @if($row->hasil_klasifikasi === 'Layak Jual')
                                    <span class="px-2 py-0.5 rounded text-xs font-extrabold bg-emerald-100 text-emerald-800">Layak Jual</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-xs font-extrabold bg-rose-100 text-rose-800">Tidak Layak Jual</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ $row->pekerja->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-slate-400">
                                Tidak ada data klasifikasi dalam rentang tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
