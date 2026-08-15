@extends('layouts.app')

@section('title', 'Riwayat Hasil Klasifikasi Telur')
@section('page-title', 'Riwayat Klasifikasi Telur')

@section('content')
<div class="space-y-6">

    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">Riwayat Klasifikasi Pemanenan</h1>
            <p class="text-xs text-slate-500 mt-0.5">Daftar telur yang telah diukur dan diklasifikasikan oleh sistem C4.5</p>
        </div>
        <a href="{{ route('klasifikasi.create') }}" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-extrabold text-xs rounded-xl shadow-lg shadow-amber-500/20 transition-all flex items-center gap-2 shrink-0">
            <i class="fa-solid fa-plus-circle"></i>
            <span>Input Telur Baru</span>
        </a>
    </div>

    <!-- Filter Card -->
    <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm">
        <form action="{{ route('klasifikasi.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div>
                <label for="search" class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Cari Kode Telur</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Contoh: TLR-..."
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label for="tanggal" class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Tanggal Panen</label>
                <input type="date" name="tanggal" id="tanggal" value="{{ request('tanggal') }}"
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label for="hasil" class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Hasil Klasifikasi</label>
                <select name="hasil" id="hasil" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-amber-500">
                    <option value="">Semua Hasil</option>
                    <option value="Layak Jual" {{ request('hasil') === 'Layak Jual' ? 'selected' : '' }}>Layak Jual</option>
                    <option value="Tidak Layak Jual" {{ request('hasil') === 'Tidak Layak Jual' ? 'selected' : '' }}>Tidak Layak Jual</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="w-full py-2 px-3 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition-all">
                    <i class="fa-solid fa-filter me-1"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'tanggal', 'hasil']))
                    <a href="{{ route('klasifikasi.index') }}" class="py-2 px-3 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs rounded-xl transition-all">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-600 font-semibold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3.5">Kode Telur</th>
                        <th class="px-4 py-3.5">Tanggal Panen</th>
                        <th class="px-4 py-3.5">Berat (Gram)</th>
                        <th class="px-4 py-3.5">Diameter (Cm)</th>
                        <th class="px-4 py-3.5">Kondisi Cangkang</th>
                        <th class="px-4 py-3.5">Warna Cangkang</th>
                        <th class="px-4 py-3.5">Hasil C4.5</th>
                        <th class="px-4 py-3.5">Petugas</th>
                        <th class="px-4 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 font-medium">
                    @forelse($items as $item)
                        <tr class="hover:bg-slate-50/80 transition-all">
                            <td class="px-4 py-3.5 font-bold text-slate-900">{{ $item->kode_telur }}</td>
                            <td class="px-4 py-3.5 text-slate-600">{{ \Carbon\Carbon::parse($item->tanggal_panen)->format('d M Y') }}</td>
                            <td class="px-4 py-3.5 font-semibold text-slate-800">{{ number_format($item->berat, 1) }} g</td>
                            <td class="px-4 py-3.5 text-slate-800">{{ number_format($item->diameter, 1) }} cm</td>
                            <td class="px-4 py-3.5">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $item->kondisi_cangkang === 'Normal' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $item->kondisi_cangkang }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-slate-700">{{ $item->warna_cangkang }}</td>
                            <td class="px-4 py-3.5">
                                @if($item->hasil_klasifikasi === 'Layak Jual')
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 inline-flex items-center gap-1">
                                        <i class="fa-solid fa-circle-check text-emerald-600"></i> Layak Jual
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200 inline-flex items-center gap-1">
                                        <i class="fa-solid fa-circle-xmark text-rose-600"></i> Tidak Layak Jual
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-slate-600">{{ $item->pekerja->name ?? 'Pekerja' }}</td>
                            <td class="px-4 py-3.5 text-right flex items-center justify-end gap-1.5">
                                <a href="{{ route('klasifikasi.show', $item->id) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-semibold transition-all">
                                    Detail
                                </a>
                                @if(Auth::user()->isAdmin())
                                    <form action="{{ route('klasifikasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2 py-1 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg transition-all">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-folder-open text-4xl mb-3 block"></i>
                                Tidak ada data klasifikasi yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($items->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50">
                {{ $items->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
