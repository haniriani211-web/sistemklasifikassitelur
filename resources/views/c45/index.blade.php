@extends('layouts.app')

@section('title', 'Detail Perhitungan Algoritma C4.5')
@section('page-title', 'Detail Perhitungan Algoritma C4.5')

@section('content')
<div class="space-y-6">

    <!-- Header Banner - Light Yolk Theme -->
    <div class="p-6 bg-gradient-to-r from-amber-100 via-amber-50 to-orange-100 border border-amber-200/80 rounded-3xl shadow-xs relative overflow-hidden">
        <div class="absolute right-0 top-0 w-80 h-80 bg-amber-300/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/90 text-amber-900 border border-amber-200/90 text-xs font-extrabold rounded-full shadow-xs mb-2">
                <i class="fa-solid fa-calculator text-amber-600"></i> Engine Transparansi C4.5 🥚
            </span>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Kalkulasi C4.5 & Visualisasi Pohon Keputusan</h1>
            <p class="text-slate-700 text-xs mt-1.5 max-w-3xl leading-relaxed font-medium">
                Halaman ini menampilkan perhitungan matematika C4.5 step-by-step sesuai formula pada <strong>RUMUS C4.5.xlsx</strong>, 
                mulai dari pencarian threshold atribut kontinu (Berat & Diameter), pembentukan Root Node, hingga pengujian matriks konfusi.
            </p>
        </div>
    </div>

    <!-- Top Key Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-[11px] font-extrabold text-slate-400 uppercase">Entropy Awal S</span>
            <h3 class="text-xl font-mono font-black text-slate-900 mt-1">{{ number_format($details['initial_entropy'], 4) }}</h3>
            <p class="text-[11px] text-slate-500 font-medium mt-0.5">S = {{ $details['layak_count'] }} Layak, {{ $details['tidak_layak_count'] }} Tidak Layak</p>
        </div>

        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-[11px] font-extrabold text-amber-800 uppercase">Root Node Terpilih</span>
            <h3 class="text-xl font-black text-amber-600 mt-1">{{ $details['best_root']['attribute'] }}</h3>
            <p class="text-[11px] text-emerald-700 font-bold mt-0.5">Gain: {{ number_format($details['best_root']['gain'], 4) }}</p>
        </div>

        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-[11px] font-extrabold text-emerald-800 uppercase">Threshold Optimal Berat</span>
            <h3 class="text-xl font-mono font-black text-emerald-600 mt-1">&le; 53.0 Gram</h3>
            <p class="text-[11px] text-slate-500 font-medium mt-0.5">Pemisah kelas sempurna (Entropy = 0)</p>
        </div>

        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-[11px] font-extrabold text-emerald-800 uppercase">Akurasi Confusion Matrix</span>
            <h3 class="text-xl font-mono font-black text-emerald-600 mt-1">{{ $details['evaluation']['accuracy'] }}%</h3>
            <p class="text-[11px] text-emerald-700 font-bold mt-0.5">Precision: {{ $details['evaluation']['precision'] }}% | Recall: {{ $details['evaluation']['recall'] }}%</p>
        </div>
    </div>

    <!-- Section Tabs -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden" x-data="{ tab: 'gains' }">
        
        <!-- Tab Navigation Bar -->
        <div class="flex items-center gap-2 px-4 pt-3 border-b border-amber-100 bg-amber-50/40 overflow-x-auto custom-scrollbar">
            <button @click="tab = 'gains'" :class="tab === 'gains' ? 'border-amber-500 text-amber-900 bg-white font-extrabold' : 'border-transparent text-slate-600 hover:text-slate-900 font-semibold'"
                class="px-4 py-2.5 text-xs rounded-t-2xl border-b-2 transition-all shrink-0 flex items-center gap-1.5">
                <i class="fa-solid fa-ranking-star text-amber-500"></i> Rekap Gain & Root
            </button>
            <button @click="tab = 'berat'" :class="tab === 'berat' ? 'border-amber-500 text-amber-900 bg-white font-extrabold' : 'border-transparent text-slate-600 hover:text-slate-900 font-semibold'"
                class="px-4 py-2.5 text-xs rounded-t-2xl border-b-2 transition-all shrink-0 flex items-center gap-1.5">
                <i class="fa-solid fa-weight-scale text-amber-500"></i> Threshold Berat Telur
            </button>
            <button @click="tab = 'diameter'" :class="tab === 'diameter' ? 'border-amber-500 text-amber-900 bg-white font-extrabold' : 'border-transparent text-slate-600 hover:text-slate-900 font-semibold'"
                class="px-4 py-2.5 text-xs rounded-t-2xl border-b-2 transition-all shrink-0 flex items-center gap-1.5">
                <i class="fa-solid fa-ruler text-amber-500"></i> Threshold Diameter Telur
            </button>
            <button @click="tab = 'tree'" :class="tab === 'tree' ? 'border-amber-500 text-amber-900 bg-white font-extrabold' : 'border-transparent text-slate-600 hover:text-slate-900 font-semibold'"
                class="px-4 py-2.5 text-xs rounded-t-2xl border-b-2 transition-all shrink-0 flex items-center gap-1.5">
                <i class="fa-solid fa-diagram-project text-amber-500"></i> Pohon Keputusan (Tree)
            </button>
            <button @click="tab = 'rules'" :class="tab === 'rules' ? 'border-amber-500 text-amber-900 bg-white font-extrabold' : 'border-transparent text-slate-600 hover:text-slate-900 font-semibold'"
                class="px-4 py-2.5 text-xs rounded-t-2xl border-b-2 transition-all shrink-0 flex items-center gap-1.5">
                <i class="fa-solid fa-code text-amber-500"></i> Rule C4.5
            </button>
            <button @click="tab = 'matrix'" :class="tab === 'matrix' ? 'border-amber-500 text-amber-900 bg-white font-extrabold' : 'border-transparent text-slate-600 hover:text-slate-900 font-semibold'"
                class="px-4 py-2.5 text-xs rounded-t-2xl border-b-2 transition-all shrink-0 flex items-center gap-1.5">
                <i class="fa-solid fa-table-cells text-amber-500"></i> Confusion Matrix
            </button>
        </div>

        <div class="p-6">
            <!-- TAB 1: Rekap Gain & Root -->
            <div x-show="tab === 'gains'" class="space-y-6">
                <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-xs text-amber-900">
                    <p class="font-bold mb-1 flex items-center gap-1">
                        <i class="fa-solid fa-lightbulb text-amber-600"></i> Penentuan Root Node (Akar Pohon):
                    </p>
                    <p class="font-medium">Atribut dengan nilai <strong>Information Gain</strong> / <strong>Gain Ratio</strong> tertinggi akan terpilih sebagai Root Node utama. Pada dataset 20 sampel telur ini, <strong>Berat Telur</strong> dengan threshold &le; 53.0 Gram menghasilkan Gain terbesar (<strong>{{ number_format($details['best_root']['gain'], 6) }}</strong>).</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left border border-slate-200 rounded-2xl overflow-hidden">
                        <thead class="bg-amber-50/70 text-slate-700 font-bold uppercase">
                            <tr>
                                <th class="px-4 py-3">Peringkat</th>
                                <th class="px-4 py-3">Nama Atribut Fisik</th>
                                <th class="px-4 py-3">Pemisah (Best Split Threshold)</th>
                                <th class="px-4 py-3">Information Gain</th>
                                <th class="px-4 py-3">Split Info</th>
                                <th class="px-4 py-3">Gain Ratio</th>
                                <th class="px-4 py-3">Status Terpilih</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 font-medium">
                            @foreach($details['summary_gains'] as $idx => $row)
                                <tr class="{{ $idx === 0 ? 'bg-amber-100/50 font-bold' : '' }}">
                                    <td class="px-4 py-3">#{{ $idx + 1 }}</td>
                                    <td class="px-4 py-3 text-slate-900 font-black">{{ $row['attribute'] }}</td>
                                    <td class="px-4 py-3 font-mono text-slate-700">{{ $row['best_split'] }}</td>
                                    <td class="px-4 py-3 font-mono font-black text-amber-700">{{ number_format($row['gain'], 6) }}</td>
                                    <td class="px-4 py-3 font-mono text-slate-600">{{ number_format($row['split_info'], 6) }}</td>
                                    <td class="px-4 py-3 font-mono text-slate-800">{{ number_format($row['gain_ratio'], 6) }}</td>
                                    <td class="px-4 py-3">
                                        @if($idx === 0)
                                            <span class="px-2.5 py-1 bg-amber-500 text-amber-950 rounded-xl text-[10px] font-black uppercase shadow-xs">
                                                Root Node Utama 🥚
                                            </span>
                                        @else
                                            <span class="text-slate-400 text-[11px] font-normal">Cabang Sekunder</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 2: Threshold Berat Telur -->
            <div x-show="tab === 'berat'" class="space-y-4">
                <h3 class="font-black text-slate-900 text-sm">Pencarian Candidate Threshold Atribut Kontinu: Berat Telur</h3>
                <p class="text-xs text-slate-500 font-medium">Algoritma C4.5 mengurutkan nilai berat, menentukan titik tengah (*midpoint*), dan menguji Gain pada setiap threshold:</p>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-xs text-left border border-slate-200 rounded-2xl overflow-hidden">
                        <thead class="bg-amber-50/70 text-slate-700 font-bold uppercase">
                            <tr>
                                <th class="px-3 py-2">No</th>
                                <th class="px-3 py-2">Nilai A</th>
                                <th class="px-3 py-2">Nilai B</th>
                                <th class="px-3 py-2">Threshold (Midpoint)</th>
                                <th class="px-3 py-2">&le; Threshold (Layak / Tidak)</th>
                                <th class="px-3 py-2">Entropy &le;</th>
                                <th class="px-3 py-2">> Threshold (Layak / Tidak)</th>
                                <th class="px-3 py-2">Entropy ></th>
                                <th class="px-3 py-2">Entropy Tertimbang</th>
                                <th class="px-3 py-2">Information Gain</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 font-mono text-[11px]">
                            @foreach($details['berat_analysis']['candidates'] as $idx => $c)
                                <tr class="{{ abs($c['threshold'] - 53.0) < 0.1 ? 'bg-amber-100 font-bold text-amber-950' : 'hover:bg-slate-50' }}">
                                    <td class="px-3 py-2 font-sans">{{ $idx + 1 }}</td>
                                    <td class="px-3 py-2">{{ number_format($c['val_a'], 1) }}</td>
                                    <td class="px-3 py-2">{{ number_format($c['val_b'], 1) }}</td>
                                    <td class="px-3 py-2 font-bold text-slate-900">&le; {{ number_format($c['threshold'], 2) }}</td>
                                    <td class="px-3 py-2">{{ $c['left_count'] }} (L: {{ $c['left_layak'] }}, T: {{ $c['left_tidak'] }})</td>
                                    <td class="px-3 py-2">{{ number_format($c['left_entropy'], 4) }}</td>
                                    <td class="px-3 py-2">{{ $c['right_count'] }} (L: {{ $c['right_layak'] }}, T: {{ $c['right_tidak'] }})</td>
                                    <td class="px-3 py-2">{{ number_format($c['right_entropy'], 4) }}</td>
                                    <td class="px-3 py-2">{{ number_format($c['weighted_entropy'], 4) }}</td>
                                    <td class="px-3 py-2 font-black text-amber-700">{{ number_format($c['gain'], 6) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 3: Threshold Diameter Telur -->
            <div x-show="tab === 'diameter'" class="space-y-4">
                <h3 class="font-black text-slate-900 text-sm">Pencarian Candidate Threshold Atribut Kontinu: Diameter Telur</h3>
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-xs text-left border border-slate-200 rounded-2xl overflow-hidden">
                        <thead class="bg-amber-50/70 text-slate-700 font-bold uppercase">
                            <tr>
                                <th class="px-3 py-2">No</th>
                                <th class="px-3 py-2">Threshold (Midpoint)</th>
                                <th class="px-3 py-2">&le; Threshold (Layak / Tidak)</th>
                                <th class="px-3 py-2">Entropy &le;</th>
                                <th class="px-3 py-2">> Threshold (Layak / Tidak)</th>
                                <th class="px-3 py-2">Entropy ></th>
                                <th class="px-3 py-2">Information Gain</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 font-mono text-[11px]">
                            @foreach($details['diameter_analysis']['candidates'] as $idx => $c)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-3 py-2 font-sans">{{ $idx + 1 }}</td>
                                    <td class="px-3 py-2 font-bold text-slate-900">&le; {{ number_format($c['threshold'], 2) }} cm</td>
                                    <td class="px-3 py-2">{{ $c['left_count'] }} (L: {{ $c['left_layak'] }}, T: {{ $c['left_tidak'] }})</td>
                                    <td class="px-3 py-2">{{ number_format($c['left_entropy'], 4) }}</td>
                                    <td class="px-3 py-2">{{ $c['right_count'] }} (L: {{ $c['right_layak'] }}, T: {{ $c['right_tidak'] }})</td>
                                    <td class="px-3 py-2">{{ number_format($c['right_entropy'], 4) }}</td>
                                    <td class="px-3 py-2 font-black text-amber-700">{{ number_format($c['gain'], 6) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 4: Visual Pohon Keputusan (Light Mode) -->
            <div x-show="tab === 'tree'" class="space-y-6">
                <h3 class="font-black text-slate-900 text-sm flex items-center gap-1.5">
                    <i class="fa-solid fa-tree text-amber-500"></i> Visualisasi Pohon Keputusan (*Decision Tree*)
                </h3>
                
                <div class="p-6 bg-gradient-to-br from-amber-50/90 via-orange-50/50 to-amber-100/40 text-slate-800 rounded-3xl border border-amber-200 shadow-xs space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-amber-400 text-amber-950 flex items-center justify-center font-black text-2xl shadow-sm border border-amber-300">
                            <i class="fa-solid fa-egg"></i>
                        </div>
                        <div>
                            <span class="text-xs text-amber-900 font-extrabold uppercase">Root Node C4.5</span>
                            <h4 class="text-lg font-black text-slate-900">BERAT TELUR (Threshold: 53.0 Gram)</h4>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-amber-200/80">
                        <!-- Left Branch -->
                        <div class="p-5 rounded-2xl bg-rose-50 border border-rose-200 space-y-2 shadow-xs">
                            <div class="flex justify-between items-center">
                                <span class="px-3 py-1 bg-rose-100 text-rose-800 rounded-lg text-xs font-mono font-bold">Cabang 1: &le; 53.0 Gram</span>
                                <span class="text-xs font-extrabold text-rose-700">Total: 6 Telur</span>
                            </div>
                            <h5 class="text-lg font-black text-rose-700 pt-2 flex items-center gap-1">
                                <i class="fa-solid fa-ban"></i> TIDAK LAYAK JUAL
                            </h5>
                            <p class="text-xs text-slate-600 font-medium">Semua 6 sampel di cabang ini memiliki bobot di bawah standar fisik minimum peternakan (Pure Leaf, Entropy = 0).</p>
                        </div>

                        <!-- Right Branch -->
                        <div class="p-5 rounded-2xl bg-emerald-50 border border-emerald-200 space-y-2 shadow-xs">
                            <div class="flex justify-between items-center">
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-lg text-xs font-mono font-bold">Cabang 2: > 53.0 Gram</span>
                                <span class="text-xs font-extrabold text-emerald-700">Total: 14 Telur</span>
                            </div>
                            <h5 class="text-lg font-black text-emerald-700 pt-2 flex items-center gap-1">
                                <i class="fa-solid fa-circle-check"></i> LAYAK JUAL
                            </h5>
                            <p class="text-xs text-slate-600 font-medium">Semua 14 sampel di cabang ini memiliki bobot fisik yang memenuhi standar penjualan peternakan (Pure Leaf, Entropy = 0).</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 5: Rule C4.5 -->
            <div x-show="tab === 'rules'" class="space-y-4">
                <h3 class="font-black text-slate-900 text-sm">Ekstraksi Aturan Klasifikasi (*IF-THEN Rules*)</h3>
                <div class="space-y-3">
                    @foreach($details['rules'] as $idx => $r)
                        <div class="p-4 rounded-2xl border border-amber-200 bg-amber-50/40 flex items-center justify-between gap-4">
                            <div class="space-y-1">
                                <span class="text-[10px] font-extrabold uppercase text-amber-900 bg-amber-200 px-2.5 py-0.5 rounded-md">Rule #{{ $idx + 1 }}</span>
                                <p class="font-mono text-xs font-extrabold text-slate-900 mt-1">{{ $r['rule_text'] }}</p>
                            </div>
                            <span class="px-3 py-1.5 rounded-xl text-xs font-black shrink-0 {{ $r['label'] === 'Layak Jual' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-rose-100 text-rose-800 border border-rose-300' }}">
                                {{ $r['label'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- TAB 6: Confusion Matrix -->
            <div x-show="tab === 'matrix'" class="space-y-6">
                <h3 class="font-black text-slate-900 text-sm">Evaluasi Performa Model (Confusion Matrix)</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Matrix Table -->
                    <div class="p-5 bg-white border border-slate-200 rounded-2xl shadow-xs space-y-3">
                        <h4 class="font-bold text-xs text-slate-700 uppercase">Matriks Konfusi (2x2)</h4>
                        <div class="grid grid-cols-3 gap-2 text-center text-xs font-bold">
                            <div class="p-2 bg-slate-100 rounded-lg text-slate-500">Prediksi \ Aktual</div>
                            <div class="p-2 bg-emerald-100 text-emerald-900 rounded-lg">Aktual: Layak Jual</div>
                            <div class="p-2 bg-rose-100 text-rose-900 rounded-lg">Aktual: Tidak Layak</div>

                            <div class="p-3 bg-emerald-100 text-emerald-900 rounded-lg font-bold flex items-center justify-center">Prediksi: Layak Jual</div>
                            <div class="p-4 bg-emerald-50 border-2 border-emerald-400 rounded-xl font-mono text-xl text-emerald-700">
                                <span>TP = {{ $details['evaluation']['tp'] }}</span>
                            </div>
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl font-mono text-xl text-slate-500">
                                <span>FP = {{ $details['evaluation']['fp'] }}</span>
                            </div>

                            <div class="p-3 bg-rose-100 text-rose-900 rounded-lg font-bold flex items-center justify-center">Prediksi: Tidak Layak</div>
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl font-mono text-xl text-slate-500">
                                <span>FN = {{ $details['evaluation']['fn'] }}</span>
                            </div>
                            <div class="p-4 bg-rose-50 border-2 border-rose-400 rounded-xl font-mono text-xl text-rose-700">
                                <span>TN = {{ $details['evaluation']['tn'] }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Evaluation Metrics - Light Theme -->
                    <div class="p-5 bg-gradient-to-b from-amber-50 to-white text-slate-800 rounded-2xl border border-amber-200 shadow-xs space-y-4">
                        <h4 class="font-black text-xs text-amber-900 uppercase tracking-wider">Metrik Pengujian Kinerja Model</h4>
                        <div class="space-y-3 text-xs font-medium">
                            <div class="flex justify-between items-center border-b border-amber-100 pb-2">
                                <span class="text-slate-600">Akurasi (Accuracy):</span>
                                <span class="font-mono font-black text-emerald-600 text-base">{{ $details['evaluation']['accuracy'] }}%</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-amber-100 pb-2">
                                <span class="text-slate-600">Presisi (Precision):</span>
                                <span class="font-mono font-black text-emerald-600 text-base">{{ $details['evaluation']['precision'] }}%</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-amber-100 pb-2">
                                <span class="text-slate-600">Sensitivitas / Recall:</span>
                                <span class="font-mono font-black text-emerald-600 text-base">{{ $details['evaluation']['recall'] }}%</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-amber-100 pb-2">
                                <span class="text-slate-600">Spesifisitas (Specificity):</span>
                                <span class="font-mono font-black text-emerald-600 text-base">{{ $details['evaluation']['specificity'] }}%</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-600">F1-Score:</span>
                                <span class="font-mono font-black text-amber-700 text-base">{{ $details['evaluation']['f1_score'] }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
