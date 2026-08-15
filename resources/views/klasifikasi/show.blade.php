@extends('layouts.app')

@section('title', 'Detail Klasifikasi Telur ' . $item->kode_telur)
@section('page-title', 'Detail Klasifikasi Telur')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Header & Navigation -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-black text-slate-900">Hasil Klasifikasi C4.5 🥚</h1>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Detail karakteristik fisik & keputusan kelayakan telur</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('klasifikasi.create') }}" class="px-3.5 py-2 bg-amber-400 hover:bg-amber-500 text-amber-950 text-xs font-black rounded-2xl shadow-sm transition-all flex items-center gap-1.5">
                <i class="fa-solid fa-plus-circle"></i>
                <span>Input Telur Lain</span>
            </a>
            <a href="{{ route('klasifikasi.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-extrabold rounded-2xl transition-all">
                Riwayat
            </a>
        </div>
    </div>

    <!-- Result Banner - Light Mode -->
    <div class="p-8 rounded-3xl shadow-sm flex flex-col md:flex-row items-center justify-between gap-6 border {{ $item->hasil_klasifikasi === 'Layak Jual' ? 'bg-emerald-500 text-white border-emerald-600' : 'bg-rose-500 text-white border-rose-600' }}">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-3xl shrink-0 font-extrabold border border-white/30 egg-float">
                @if($item->hasil_klasifikasi === 'Layak Jual')
                    <i class="fa-solid fa-egg"></i>
                @else
                    <i class="fa-solid fa-heart-crack"></i>
                @endif
            </div>
            <div>
                <span class="text-xs font-black uppercase tracking-wider text-white/90">Hasil Klasifikasi C4.5</span>
                <h2 class="text-3xl font-black tracking-tight mt-0.5">{{ strtoupper($item->hasil_klasifikasi) }}</h2>
                <p class="text-xs text-white/90 mt-1 font-medium">Kode Telur: <strong>{{ $item->kode_telur }}</strong> | Panen: {{ \Carbon\Carbon::parse($item->tanggal_panen)->format('d M Y') }}</p>
            </div>
        </div>

        <div class="text-right shrink-0">
            <span class="inline-block px-3.5 py-1.5 bg-white/20 backdrop-blur-sm rounded-full text-xs font-extrabold border border-white/30">
                Terdaftar di Sistem
            </span>
        </div>
    </div>

    <!-- Applied Rule Box - Light Yolk Theme -->
    <div class="p-5 bg-amber-50/80 text-slate-800 rounded-3xl border border-amber-200 shadow-xs space-y-2">
        <div class="flex items-center gap-2 text-amber-900 font-black text-xs">
            <i class="fa-solid fa-code-branch text-amber-600"></i>
            <span>Aturan (Rule) C4.5 yang Diterapkan:</span>
        </div>
        <p class="font-mono text-xs font-extrabold text-amber-950 bg-white p-3.5 rounded-2xl border border-amber-200 shadow-xs">
            {{ $item->rule_applied ?? 'IF Berat > 53.0 Gram THEN Layak Jual' }}
        </p>
    </div>

    <!-- Physical Features Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs p-6 space-y-5">
        <h3 class="font-black text-slate-900 text-sm border-b border-slate-100 pb-3 flex items-center gap-2">
            <i class="fa-solid fa-clipboard-list text-amber-500"></i>
            Rincian Karakteristik Fisik Telur
        </h3>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="p-4 rounded-2xl bg-amber-50/40 border border-amber-100 text-center">
                <span class="block text-[11px] font-extrabold text-slate-400 uppercase">Berat Telur</span>
                <span class="block text-xl font-black text-slate-900 mt-1">{{ number_format($item->berat, 1) }}</span>
                <span class="text-xs font-bold text-slate-500">Gram</span>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-center">
                <span class="block text-[11px] font-extrabold text-slate-400 uppercase">Diameter Telur</span>
                <span class="block text-xl font-black text-slate-900 mt-1">{{ number_format($item->diameter, 1) }}</span>
                <span class="text-xs font-bold text-slate-500">Cm</span>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-center">
                <span class="block text-[11px] font-extrabold text-slate-400 uppercase">Kondisi Cangkang</span>
                <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-black {{ $item->kondisi_cangkang === 'Normal' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                    {{ $item->kondisi_cangkang }}
                </span>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-center">
                <span class="block text-[11px] font-extrabold text-slate-400 uppercase">Warna Cangkang</span>
                <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-black bg-amber-100 text-amber-900">
                    {{ $item->warna_cangkang }}
                </span>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs text-slate-600">
            <div>
                <span class="block text-slate-400 font-bold">Petugas Pekerja Kandang:</span>
                <span class="font-extrabold text-slate-900">{{ $item->pekerja->name ?? 'Pekerja Kandang' }}</span>
            </div>
            <div>
                <span class="block text-slate-400 font-bold">Catatan / Keterangan:</span>
                <span class="font-semibold text-slate-800">{{ $item->catatan ?? '-' }}</span>
            </div>
        </div>
    </div>

</div>
@endsection
