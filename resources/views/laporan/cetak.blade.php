<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Klasifikasi Kelayakan Telur - Peternakan Rajadesa</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; font-size: 11px; }
            @page { margin: 1.5cm; }
        }
    </style>
</head>
<body class="bg-slate-100 font-sans text-slate-900 p-6 min-h-screen">

    <!-- Print Button Bar -->
    <div class="max-w-4xl mx-auto mb-6 flex justify-between items-center no-print">
        <button onclick="window.close()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl">
            Tutup Windows
        </button>
        <button onclick="window.print()" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-extrabold rounded-xl shadow-lg">
            Cetak Dokumen Laporan
        </button>
    </div>

    <!-- Official Report Document Paper -->
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-xl border border-slate-200 space-y-6">

        <!-- Kop Surat Header -->
        <div class="border-b-2 border-slate-900 pb-4 text-center">
            <h1 class="text-2xl font-black uppercase tracking-wider text-slate-900">PETERNAKAN AYAM PETELUR RAJADESA</h1>
            <p class="text-xs text-slate-600 mt-1">Sistem Informasi Klasifikasi Kelayakan Kualitas Telur Berdasarkan Karakteristik Fisik</p>
            <p class="text-[11px] text-slate-500 italic mt-0.5">Metode Algoritma Decision Tree C4.5 | Desa Rajadesa, Kabupaten Ciamis</p>
        </div>

        <!-- Title of Report -->
        <div class="text-center space-y-1">
            <h2 class="text-base font-extrabold uppercase underline tracking-wide">LAPORAN HASIL REKAPITULASI KLASIFIKASI TELUR</h2>
            <p class="text-xs text-slate-600">
                Periode Panen: <strong>{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}</strong> s/d <strong>{{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</strong>
            </p>
        </div>

        <!-- Summary Boxes -->
        <div class="grid grid-cols-3 gap-4 text-center text-xs">
            <div class="p-3 bg-slate-50 border border-slate-300 rounded-xl">
                <span class="block text-slate-500 font-bold uppercase text-[10px]">Total Panen</span>
                <span class="block text-lg font-black text-slate-900 mt-0.5">{{ number_format($total) }} Butir</span>
            </div>
            <div class="p-3 bg-emerald-50 border border-emerald-300 rounded-xl">
                <span class="block text-emerald-800 font-bold uppercase text-[10px]">Layak Jual</span>
                <span class="block text-lg font-black text-emerald-700 mt-0.5">{{ number_format($totalLayak) }} Butir</span>
            </div>
            <div class="p-3 bg-rose-50 border border-rose-300 rounded-xl">
                <span class="block text-rose-800 font-bold uppercase text-[10px]">Tidak Layak Jual</span>
                <span class="block text-lg font-black text-rose-700 mt-0.5">{{ number_format($totalTidakLayak) }} Butir</span>
            </div>
        </div>

        <!-- Table Data -->
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse border border-slate-300">
                <thead class="bg-slate-100 text-slate-800 uppercase font-bold text-[10px]">
                    <tr>
                        <th class="border border-slate-300 px-3 py-2 text-center">No</th>
                        <th class="border border-slate-300 px-3 py-2">Kode Telur</th>
                        <th class="border border-slate-300 px-3 py-2">Tgl Panen</th>
                        <th class="border border-slate-300 px-3 py-2 text-right">Berat (g)</th>
                        <th class="border border-slate-300 px-3 py-2 text-right">Diameter (cm)</th>
                        <th class="border border-slate-300 px-3 py-2">Cangkang</th>
                        <th class="border border-slate-300 px-3 py-2">Warna</th>
                        <th class="border border-slate-300 px-3 py-2">Hasil C4.5</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-300">
                    @forelse($items as $idx => $row)
                        <tr>
                            <td class="border border-slate-300 px-3 py-2 text-center font-bold text-slate-500">{{ $idx + 1 }}</td>
                            <td class="border border-slate-300 px-3 py-2 font-bold">{{ $row->kode_telur }}</td>
                            <td class="border border-slate-300 px-3 py-2">{{ \Carbon\Carbon::parse($row->tanggal_panen)->format('d/m/Y') }}</td>
                            <td class="border border-slate-300 px-3 py-2 text-right font-semibold">{{ number_format($row->berat, 1) }}</td>
                            <td class="border border-slate-300 px-3 py-2 text-right">{{ number_format($row->diameter, 1) }}</td>
                            <td class="border border-slate-300 px-3 py-2">{{ $row->kondisi_cangkang }}</td>
                            <td class="border border-slate-300 px-3 py-2">{{ $row->warna_cangkang }}</td>
                            <td class="border border-slate-300 px-3 py-2 font-bold {{ $row->hasil_klasifikasi === 'Layak Jual' ? 'text-emerald-700' : 'text-rose-700' }}">
                                {{ $row->hasil_klasifikasi }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="border border-slate-300 px-3 py-6 text-center text-slate-400">
                                Tidak ada data klasifikasi ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Signatures -->
        <div class="pt-8 grid grid-cols-2 text-xs text-center">
            <div>
                <p>Mengetahui,</p>
                <p class="font-bold text-slate-900 mt-1">Petugas Pekerja Kandang</p>
                <div class="h-16"></div>
                <p class="font-bold underline">( ............................................ )</p>
            </div>
            <div>
                <p>Rajadesa, {{ date('d F Y') }}</p>
                <p class="font-bold text-slate-900 mt-1">Administrasi Peternakan</p>
                <div class="h-16"></div>
                <p class="font-bold underline">( Administrasi Rajadesa )</p>
            </div>
        </div>

    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>
