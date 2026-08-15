@extends('layouts.app')

@section('title', 'Dataset Latih C4.5')
@section('page-title', 'Manajemen Dataset Latih C4.5')

@section('content')
<div class="space-y-6">

    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">Dataset Latih Algoritma C4.5</h1>
            <p class="text-xs text-slate-500 mt-0.5">Data sampel historis yang digunakan oleh mesin C4.5 untuk membentuk pohon keputusan (*Decision Tree*)</p>
        </div>
        <div class="flex items-center gap-2">
            <form action="{{ route('dataset.reset') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mereset dataset ke 20 sampel awal dari spreadsheet RUMUS C4.5.xlsx?')">
                @csrf
                <button type="submit" class="px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition-all flex items-center gap-1.5 border border-slate-300">
                    <i class="fa-solid fa-rotate-left"></i>
                    <span>Reset Ke Data Awal</span>
                </button>
            </form>
            <button onclick="toggleModal('modalAddDataset')" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-extrabold text-xs rounded-xl shadow-lg shadow-amber-500/20 transition-all flex items-center gap-1.5 shrink-0">
                <i class="fa-solid fa-plus-circle"></i>
                <span>Tambah Data Latih</span>
            </button>
        </div>
    </div>

    <!-- Dataset Summary Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500">Total Sampel Latih</span>
                <h3 class="text-xl font-extrabold text-slate-900 mt-0.5">{{ $totalCount }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                <i class="fa-solid fa-database"></i>
            </div>
        </div>

        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500">Sampel Layak Jual</span>
                <h3 class="text-xl font-extrabold text-emerald-600 mt-0.5">{{ $layakCount }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <i class="fa-solid fa-check-double"></i>
            </div>
        </div>

        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500">Sampel Tidak Layak Jual</span>
                <h3 class="text-xl font-extrabold text-rose-600 mt-0.5">{{ $tidakLayakCount }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                <i class="fa-solid fa-ban"></i>
            </div>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-600 font-semibold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3.5">ID Telur</th>
                        <th class="px-4 py-3.5">Berat (Gram)</th>
                        <th class="px-4 py-3.5">Diameter (Cm)</th>
                        <th class="px-4 py-3.5">Kondisi Cangkang</th>
                        <th class="px-4 py-3.5">Warna Cangkang</th>
                        <th class="px-4 py-3.5">Kualitas (Label Target)</th>
                        <th class="px-4 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 font-medium">
                    @forelse($dataset as $row)
                        <tr class="hover:bg-slate-50/80 transition-all">
                            <td class="px-4 py-3.5 font-bold text-slate-900">{{ $row->kode_telur }}</td>
                            <td class="px-4 py-3.5 font-semibold text-slate-800">{{ number_format($row->berat, 1) }} g</td>
                            <td class="px-4 py-3.5 text-slate-800">{{ number_format($row->diameter, 1) }} cm</td>
                            <td class="px-4 py-3.5">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $row->kondisi_cangkang === 'Normal' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $row->kondisi_cangkang }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-slate-700">{{ $row->warna_cangkang }}</td>
                            <td class="px-4 py-3.5">
                                @if($row->kualitas === 'Layak Jual')
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        Layak Jual
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                        Tidak Layak Jual
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-right flex items-center justify-end gap-1.5">
                                <form action="{{ route('dataset.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Hapus data latih ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg transition-all">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-400">
                                Dataset kosong. Klik "Reset Ke Data Awal" untuk mengisi 20 sampel dari spreadsheet RUMUS C4.5.xlsx.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($dataset->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50">
                {{ $dataset->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal Add Dataset -->
<div id="modalAddDataset" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="font-extrabold text-slate-900 text-base">Tambah Data Latih Baru</h3>
            <button onclick="toggleModal('modalAddDataset')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('dataset.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="kode_telur" class="block text-xs font-bold text-slate-700 uppercase mb-1">ID / Kode Telur</label>
                <input type="text" name="kode_telur" required placeholder="Contoh: TLR-20260421-021"
                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-900">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="berat" class="block text-xs font-bold text-slate-700 uppercase mb-1">Berat (Gram)</label>
                    <input type="number" step="0.1" name="berat" required placeholder="60.0"
                        class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-xs font-bold text-slate-900">
                </div>
                <div>
                    <label for="diameter" class="block text-xs font-bold text-slate-700 uppercase mb-1">Diameter (Cm)</label>
                    <input type="number" step="0.1" name="diameter" required placeholder="4.2"
                        class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-xs font-bold text-slate-900">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="kondisi_cangkang" class="block text-xs font-bold text-slate-700 uppercase mb-1">Kondisi Cangkang</label>
                    <select name="kondisi_cangkang" required class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-xs font-semibold">
                        <option value="Normal">Normal</option>
                        <option value="Retak">Retak</option>
                    </select>
                </div>
                <div>
                    <label for="warna_cangkang" class="block text-xs font-bold text-slate-700 uppercase mb-1">Warna Cangkang</label>
                    <select name="warna_cangkang" required class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-xs font-semibold">
                        <option value="Cokelat Tua">Cokelat Tua</option>
                        <option value="Cokelat Muda">Cokelat Muda</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="kualitas" class="block text-xs font-bold text-slate-700 uppercase mb-1">Label Kualitas Target</label>
                <select name="kualitas" required class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-xs font-extrabold text-amber-800">
                    <option value="Layak Jual">Layak Jual</option>
                    <option value="Tidak Layak Jual">Tidak Layak Jual</option>
                </select>
            </div>

            <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" onclick="toggleModal('modalAddDataset')" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl">Batal</button>
                <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-extrabold rounded-xl">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(modalId) {
        const el = document.getElementById(modalId);
        if(el) el.classList.toggle('hidden');
    }
</script>
@endsection
