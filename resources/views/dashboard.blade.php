@extends('layouts.app')

@section('title', 'Dashboard - Sistem C4.5 Telur')
@section('page-title', 'Dashboard Utama')

@section('content')
<div class="space-y-6">

    <!-- Welcome Banner - Light Yolk Theme -->
    <div class="p-6 rounded-3xl bg-gradient-to-r from-amber-100 via-amber-50 to-orange-100 border border-amber-200/80 shadow-xs relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-amber-300/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/90 text-amber-900 border border-amber-200/90 text-xs font-extrabold rounded-full shadow-xs mb-2">
                    <i class="fa-solid fa-egg text-amber-600 egg-wiggle"></i> Peternakan Ayam Petelur Rajadesa
                </span>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Selamat Datang, {{ Auth::user()->name }}! 🪺</h1>
                <p class="text-slate-700 text-xs mt-1.5 max-w-2xl leading-relaxed font-medium">
                    Sistem klasifikasi kelayakan kualitas telur (*Layak Jual* vs *Tidak Layak Jual*) mengolah karakteristik fisik berupa 
                    <strong class="text-amber-900 bg-amber-200/60 px-1.5 py-0.5 rounded">Berat</strong>, 
                    <strong class="text-amber-900 bg-amber-200/60 px-1.5 py-0.5 rounded">Diameter</strong>, 
                    <strong class="text-amber-900 bg-amber-200/60 px-1.5 py-0.5 rounded">Kondisi Cangkang</strong>, dan 
                    <strong class="text-amber-900 bg-amber-200/60 px-1.5 py-0.5 rounded">Warna Cangkang</strong> menggunakan Algoritma C4.5.
                </p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('klasifikasi.create') }}" class="px-5 py-3 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-500 hover:to-amber-600 text-amber-950 font-black text-xs rounded-2xl shadow-md shadow-amber-400/30 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-plus-circle text-sm"></i>
                    <span>Input Panen Telur</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Stat 1: Total Panen -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs flex items-center justify-between hover:shadow-md transition-all">
            <div>
                <p class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Total Telur Dipanen</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ number_format($totalPanen) }} <span class="text-xs font-bold text-slate-400">butir</span></h3>
                <p class="text-xs text-slate-500 font-medium mt-1">Data hasil pemanenan pekerja</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-black border border-blue-100 shrink-0">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
        </div>

        <!-- Stat 2: Layak Jual -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs flex items-center justify-between hover:shadow-md transition-all">
            <div>
                <p class="text-[11px] font-extrabold text-emerald-600 uppercase tracking-wider">Layak Jual</p>
                <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ number_format($totalLayak) }} <span class="text-xs font-bold text-emerald-500">({{ $layakPercentage }}%)</span></h3>
                <p class="text-xs text-emerald-700 font-bold mt-1 flex items-center gap-1"><i class="fa-solid fa-circle-check"></i> Memenuhi kriteria C4.5</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-black border border-emerald-100 shrink-0">
                <i class="fa-solid fa-egg text-emerald-500"></i>
            </div>
        </div>

        <!-- Stat 3: Tidak Layak Jual -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs flex items-center justify-between hover:shadow-md transition-all">
            <div>
                <p class="text-[11px] font-extrabold text-rose-600 uppercase tracking-wider">Tidak Layak Jual</p>
                <h3 class="text-2xl font-black text-rose-600 mt-1">{{ number_format($totalTidakLayak) }} <span class="text-xs font-bold text-rose-500">({{ 100 - $layakPercentage }}%)</span></h3>
                <p class="text-xs text-rose-700 font-bold mt-1 flex items-center gap-1"><i class="fa-solid fa-heart-crack"></i> Retak / Bobot &le; 53g</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl font-black border border-rose-100 shrink-0">
                <i class="fa-solid fa-ban"></i>
            </div>
        </div>

        <!-- Stat 4: Dataset Latih -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs flex items-center justify-between hover:shadow-md transition-all">
            <div>
                <p class="text-[11px] font-extrabold text-amber-700 uppercase tracking-wider">Dataset Latih C4.5</p>
                <h3 class="text-2xl font-black text-amber-600 mt-1">{{ number_format($totalDataset) }} <span class="text-xs font-bold text-slate-400">sampel</span></h3>
                <p class="text-xs text-amber-800 font-bold mt-1 flex items-center gap-1"><i class="fa-solid fa-bullseye me-0.5"></i> Threshold: &le; 53.0 Gram</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-black border border-amber-100 shrink-0">
                <i class="fa-solid fa-database"></i>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Chart Section (2 cols) -->
        <div class="lg:col-span-2 p-6 bg-white rounded-3xl border border-slate-200 shadow-xs flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-black text-slate-900 text-base flex items-center gap-2">
                        <i class="fa-solid fa-chart-column text-amber-500"></i>
                        Grafik Hasil Klasifikasi Panen (7 Hari Terakhir)
                    </h3>
                    <p class="text-xs text-slate-500 font-medium">Distribusi harian telur Layak Jual vs Tidak Layak Jual</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Layak Jual
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-rose-700 bg-rose-50 px-2.5 py-1 rounded-full border border-rose-200">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> Tidak Layak
                    </span>
                </div>
            </div>

            <!-- Custom 100% Guaranteed Visual Bar Chart (HTML/CSS) -->
            <div class="p-4 rounded-2xl bg-amber-50/40 border border-amber-100 space-y-4">
                <div class="grid grid-cols-7 gap-2 items-end h-44 pt-6 pb-2 border-b border-amber-200/60">
                    @foreach($chartPanen as $day)
                        @php
                            $maxVal = max(1, max(array_column($chartPanen, 'total')));
                            $layakHeight = ($day['layak'] / $maxVal) * 100;
                            $tidakHeight = ($day['tidak_layak'] / $maxVal) * 100;
                        @endphp
                        <div class="flex flex-col items-center gap-1 h-full justify-end group relative">
                            <!-- Tooltip on Hover -->
                            <div class="absolute -top-10 hidden group-hover:flex flex-col items-center bg-slate-900 text-white text-[10px] py-1 px-2 rounded-lg font-bold z-20 whitespace-nowrap shadow-lg">
                                <span>{{ $day['date'] }}: {{ $day['layak'] }} Layak, {{ $day['tidak_layak'] }} Tidak</span>
                            </div>

                            <div class="w-full flex items-end justify-center gap-1 h-32 px-1">
                                <!-- Layak Bar -->
                                <div class="w-1/2 bg-emerald-500 hover:bg-emerald-600 rounded-t-md transition-all relative flex items-start justify-center" style="height: {{ max(10, $layakHeight) }}%">
                                    @if($day['layak'] > 0)
                                        <span class="text-[10px] font-black text-white -mt-4">{{ $day['layak'] }}</span>
                                    @endif
                                </div>
                                <!-- Tidak Layak Bar -->
                                <div class="w-1/2 bg-rose-500 hover:bg-rose-600 rounded-t-md transition-all relative flex items-start justify-center" style="height: {{ max(10, $tidakHeight) }}%">
                                    @if($day['tidak_layak'] > 0)
                                        <span class="text-[10px] font-black text-white -mt-4">{{ $day['tidak_layak'] }}</span>
                                    @endif
                                </div>
                            </div>
                            <span class="text-[11px] font-bold text-slate-600">{{ $day['date'] }}</span>
                        </div>
                    @endforeach
                </div>

                <!-- Legend & Summary Footer -->
                <div class="flex justify-between items-center text-xs text-slate-600 font-semibold px-2">
                    <span class="flex items-center gap-1 text-slate-700">
                        <i class="fa-solid fa-egg text-amber-500"></i> Total Panen 7 Hari: <strong>{{ array_sum(array_column($chartPanen, 'total')) }} Butir</strong>
                    </span>
                    <span class="text-emerald-700 font-bold">
                        Akumulasi: {{ array_sum(array_column($chartPanen, 'layak')) }} Layak Jual | {{ array_sum(array_column($chartPanen, 'tidak_layak')) }} Tidak Layak
                    </span>
                </div>
            </div>

            <!-- Canvas for Chart.js (Optional Extra Rendering) -->
            <div class="h-44 hidden sm:block">
                <canvas id="panenChart"></canvas>
            </div>
        </div>

        <!-- C4.5 Engine Model Card - Light Yolk Theme -->
        <div class="p-6 bg-gradient-to-b from-amber-500/10 via-amber-500/5 to-white rounded-3xl border border-amber-200 shadow-xs flex flex-col justify-between relative overflow-hidden">
            <div>
                <div class="flex items-center justify-between border-b border-amber-200/80 pb-4 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="w-8 h-8 rounded-xl bg-amber-400 text-amber-950 flex items-center justify-center font-black text-base shadow-xs">
                            <i class="fa-solid fa-brain"></i>
                        </span>
                        <h3 class="font-black text-slate-900 text-base">Model Algoritma C4.5</h3>
                    </div>
                    <span class="px-2.5 py-0.5 text-[10px] font-black uppercase rounded-md bg-emerald-100 text-emerald-800 border border-emerald-300">
                        Aktif
                    </span>
                </div>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between items-center border-b border-amber-100 pb-2">
                        <span class="text-slate-600 font-medium">Entropy Awal S:</span>
                        <span class="font-mono font-extrabold text-amber-900">{{ number_format($c45Stats['initial_entropy'], 4) }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-amber-100 pb-2">
                        <span class="text-slate-600 font-medium">Root Node Terpilih:</span>
                        <span class="font-bold text-slate-900">{{ $c45Stats['best_root']['attribute'] }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-amber-100 pb-2">
                        <span class="text-slate-600 font-medium">Threshold Split Berat:</span>
                        <span class="font-mono font-black text-emerald-700">&le; 53.0 Gram</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-amber-100 pb-2">
                        <span class="text-slate-600 font-medium">Akurasi Confusion Matrix:</span>
                        <span class="font-mono font-black text-emerald-700 text-sm">{{ $c45Stats['evaluation']['accuracy'] }}%</span>
                    </div>
                </div>

                <div class="mt-5 p-3.5 rounded-2xl bg-white border border-amber-200 text-xs text-slate-700 shadow-xs">
                    <p class="font-bold text-amber-900 mb-1 flex items-center gap-1">
                        <i class="fa-solid fa-lightbulb text-amber-500"></i> Aturan Utama C4.5:
                    </p>
                    <ul class="space-y-1 text-[11px] list-disc list-inside text-slate-700 font-medium">
                        <li><strong>IF Berat &le; 53.0 Gram</strong> THEN <em>Tidak Layak Jual</em></li>
                        <li><strong>IF Berat > 53.0 Gram</strong> THEN <em>Layak Jual</em></li>
                    </ul>
                </div>
            </div>

            @if(Auth::user()->isAdmin())
                <div class="mt-6">
                    <a href="{{ route('c45.index') }}" class="w-full py-3 px-3 bg-amber-400 hover:bg-amber-500 text-amber-950 font-black text-xs rounded-2xl text-center shadow-md shadow-amber-400/20 transition-all block">
                        <i class="fa-solid fa-diagram-project me-1"></i> Detail Perhitungan C4.5 & Tree
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Panen Table -->
    <div class="p-6 bg-white rounded-3xl border border-slate-200 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
            <div>
                <h3 class="font-black text-slate-900 text-base flex items-center gap-2">
                    <i class="fa-solid fa-egg text-amber-500"></i> Hasil Klasifikasi Pemanenan Terbaru
                </h3>
                <p class="text-xs text-slate-500 font-medium">Data fisik telur yang baru dimasukkan oleh pekerja kandang</p>
            </div>
            <a href="{{ route('klasifikasi.index') }}" class="text-xs font-black text-amber-700 hover:text-amber-800 flex items-center gap-1">
                <span>Lihat Semua Riwayat</span>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-xs text-left">
                <thead class="bg-amber-50/60 text-slate-700 font-bold uppercase tracking-wider border-y border-amber-100">
                    <tr>
                        <th class="px-4 py-3.5">Kode Telur</th>
                        <th class="px-4 py-3.5">Tanggal Panen</th>
                        <th class="px-4 py-3.5">Berat (Gram)</th>
                        <th class="px-4 py-3.5">Diameter (Cm)</th>
                        <th class="px-4 py-3.5">Kondisi Cangkang</th>
                        <th class="px-4 py-3.5">Warna Cangkang</th>
                        <th class="px-4 py-3.5">Hasil C4.5</th>
                        <th class="px-4 py-3.5">Pekerja</th>
                        <th class="px-4 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($recentPanen as $item)
                        <tr class="hover:bg-amber-50/30 transition-all">
                            <td class="px-4 py-3.5 font-black text-slate-900">{{ $item->kode_telur }}</td>
                            <td class="px-4 py-3.5 text-slate-600">{{ \Carbon\Carbon::parse($item->tanggal_panen)->format('d M Y') }}</td>
                            <td class="px-4 py-3.5 font-extrabold text-slate-900">{{ number_format($item->berat, 1) }} g</td>
                            <td class="px-4 py-3.5 text-slate-800">{{ number_format($item->diameter, 1) }} cm</td>
                            <td class="px-4 py-3.5">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $item->kondisi_cangkang === 'Normal' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-rose-100 text-rose-800 border border-rose-200' }}">
                                    {{ $item->kondisi_cangkang }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-slate-700 font-medium">{{ $item->warna_cangkang }}</td>
                            <td class="px-4 py-3.5">
                                @if($item->hasil_klasifikasi === 'Layak Jual')
                                    <span class="px-2.5 py-1 rounded-xl text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-200 inline-flex items-center gap-1">
                                        <i class="fa-solid fa-circle-check text-emerald-600"></i> Layak Jual
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-xl text-xs font-black bg-rose-100 text-rose-800 border border-rose-200 inline-flex items-center gap-1">
                                        <i class="fa-solid fa-circle-xmark text-rose-600"></i> Tidak Layak Jual
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-slate-600 font-semibold">{{ $item->pekerja->name ?? 'Pekerja' }}</td>
                            <td class="px-4 py-3.5 text-right">
                                <a href="{{ route('klasifikasi.show', $item->id) }}" class="px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-900 rounded-xl font-bold transition-all">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-slate-400">
                                <i class="fa-solid fa-egg text-3xl mb-2 text-amber-300 block"></i>
                                Belum ada data pemanenan telur. Klik "Input Panen Telur" untuk memasukkan data baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('panenChart');
        if(!canvas) return;
        const ctx = canvas.getContext('2d');
        const chartData = @json($chartPanen);

        const labels = chartData.map(item => item.date);
        const dataLayak = chartData.map(item => item.layak);
        const dataTidak = chartData.map(item => item.tidak_layak);

        if(typeof Chart !== 'undefined') {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Layak Jual',
                            data: dataLayak,
                            backgroundColor: '#10b981',
                            borderRadius: 6,
                        },
                        {
                            label: 'Tidak Layak Jual',
                            data: dataTidak,
                            backgroundColor: '#f43f5e',
                            borderRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: { family: 'Plus Jakarta Sans', size: 11, weight: 'bold' }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
