@extends('layouts.app')

@section('title', 'Input Panen Telur - C4.5')
@section('page-title', 'Input Karakteristik Fisik Telur')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">Form Pemanenan Telur Kandang</h1>
            <p class="text-xs text-slate-500 mt-1">Masukkan karakteristik fisik telur untuk diklasifikasikan secara otomatis oleh Algoritma C4.5</p>
        </div>
        <a href="{{ route('klasifikasi.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-all flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Riwayat</span>
        </a>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center font-bold text-lg border border-amber-500/20">
                <i class="fa-solid fa-clipboard-check"></i>
            </div>
            <div>
                <h3 class="font-extrabold text-slate-900 text-sm">Pengukuran Karakteristik Fisik</h3>
                <p class="text-xs text-slate-500">Hasil klasifikasi akan ditentukan oleh aturan (*rule*) C4.5 secara otomatis</p>
            </div>
        </div>

        <form action="{{ route('klasifikasi.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Kode Telur -->
                <div>
                    <label for="kode_telur" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kode / ID Telur</label>
                    <input type="text" name="kode_telur" id="kode_telur" value="{{ old('kode_telur', $nextKode) }}" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-bold text-slate-900 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                    <p class="text-[11px] text-slate-400 mt-1">Nomor identifikasi unik butir telur</p>
                </div>

                <!-- Tanggal Panen -->
                <div>
                    <label for="tanggal_panen" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tanggal Pemanenan</label>
                    <input type="date" name="tanggal_panen" id="tanggal_panen" value="{{ old('tanggal_panen', date('Y-m-d')) }}" required
                        class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                </div>
            </div>

            <div class="border-t border-slate-100 pt-5 space-y-5">
                <h4 class="text-xs font-bold text-amber-800 uppercase tracking-wider flex items-center gap-1.5">
                    <i class="fa-solid fa-ruler-combined"></i> Karakteristik Fisik Utama
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Berat Telur -->
                    <div class="p-4 rounded-xl bg-amber-50/50 border border-amber-200/60">
                        <label for="berat" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                            Berat Telur (Gram) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" step="0.1" name="berat" id="berat" value="{{ old('berat') }}" placeholder="Contoh: 61.5" required
                                class="w-full pl-4 pr-16 py-2.5 bg-white border border-slate-300 rounded-xl text-base font-extrabold text-slate-900 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                            <span class="absolute inset-y-0 right-0 pr-4 flex items-center text-xs font-bold text-slate-500 pointer-events-none">Gram</span>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1.5"><i class="fa-solid fa-circle-info text-amber-600"></i> Threshold C4.5: &le; 53.0 Gram diprediksi <strong>Tidak Layak</strong></p>
                    </div>

                    <!-- Diameter Telur -->
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                        <label for="diameter" class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-1">
                            Diameter Telur (Cm) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" step="0.1" name="diameter" id="diameter" value="{{ old('diameter') }}" placeholder="Contoh: 4.2" required
                                class="w-full pl-4 pr-16 py-2.5 bg-white border border-slate-300 rounded-xl text-base font-extrabold text-slate-900 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                            <span class="absolute inset-y-0 right-0 pr-4 flex items-center text-xs font-bold text-slate-500 pointer-events-none">Cm</span>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1.5"><i class="fa-solid fa-circle-info"></i> Rentang umum: 3.5 Cm - 4.6 Cm</p>
                    </div>

                    <!-- Kondisi Cangkang -->
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                        <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-2">
                            Kondisi Cangkang <span class="text-rose-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center gap-2 p-3 rounded-xl bg-white border border-slate-300 cursor-pointer hover:border-emerald-500 transition-all has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/50">
                                <input type="radio" name="kondisi_cangkang" value="Normal" {{ old('kondisi_cangkang', 'Normal') === 'Normal' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                                <span class="text-xs font-bold text-emerald-800 flex items-center gap-1">
                                    <i class="fa-solid fa-shield"></i> Normal
                                </span>
                            </label>
                            <label class="flex items-center gap-2 p-3 rounded-xl bg-white border border-slate-300 cursor-pointer hover:border-rose-500 transition-all has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50/50">
                                <input type="radio" name="kondisi_cangkang" value="Retak" {{ old('kondisi_cangkang') === 'Retak' ? 'checked' : '' }} class="text-rose-600 focus:ring-rose-500">
                                <span class="text-xs font-bold text-rose-800 flex items-center gap-1">
                                    <i class="fa-solid fa-heart-crack"></i> Retak
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Warna Cangkang -->
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                        <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-2">
                            Warna Cangkang <span class="text-rose-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center gap-2 p-3 rounded-xl bg-white border border-slate-300 cursor-pointer hover:border-amber-700 transition-all has-[:checked]:border-amber-700 has-[:checked]:bg-amber-100/50">
                                <input type="radio" name="warna_cangkang" value="Cokelat Tua" {{ old('warna_cangkang', 'Cokelat Tua') === 'Cokelat Tua' ? 'checked' : '' }} class="text-amber-800 focus:ring-amber-700">
                                <span class="text-xs font-bold text-amber-900 flex items-center gap-1">
                                    <i class="fa-solid fa-circle text-amber-900"></i> Cokelat Tua
                                </span>
                            </label>
                            <label class="flex items-center gap-2 p-3 rounded-xl bg-white border border-slate-300 cursor-pointer hover:border-amber-500 transition-all has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50">
                                <input type="radio" name="warna_cangkang" value="Cokelat Muda" {{ old('warna_cangkang') === 'Cokelat Muda' ? 'checked' : '' }} class="text-amber-500 focus:ring-amber-500">
                                <span class="text-xs font-bold text-amber-700 flex items-center gap-1">
                                    <i class="fa-solid fa-circle text-amber-400"></i> Cokelat Muda
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Catatan Tambahan -->
            <div>
                <label for="catatan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Catatan Tambahan (Opsional)</label>
                <input type="text" name="catatan" id="catatan" value="{{ old('catatan') }}" placeholder="Contoh: Panen Kandang Blok B Pagi"
                    class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
            </div>

            <!-- Submit Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('klasifikasi.index') }}" class="px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition-all">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-amber-500 hover:bg-amber-600 text-slate-950 font-extrabold text-sm rounded-xl shadow-lg shadow-amber-500/25 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-calculator"></i>
                    <span>Proses Klasifikasi C4.5</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
