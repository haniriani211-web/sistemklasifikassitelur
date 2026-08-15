<?php

namespace App\Http\Controllers;

use App\Models\KlasifikasiTelur;
use App\Services\C45Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KlasifikasiController extends Controller
{
    protected C45Service $c45Service;

    public function __construct(C45Service $c45Service)
    {
        $this->c45Service = $c45Service;
    }

    public function index(Request $request)
    {
        $query = KlasifikasiTelur::with('pekerja')->latest();

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_panen', $request->tanggal);
        }

        if ($request->filled('hasil')) {
            $query->where('hasil_klasifikasi', $request->hasil);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_telur', 'like', "%{$search}%")
                  ->orWhere('catatan', 'like', "%{$search}%");
            });
        }

        $items = $query->paginate(15)->withQueryString();

        return view('klasifikasi.index', compact('items'));
    }

    public function create()
    {
        $nextKode = 'PN-' . date('Ymd') . '-' . str_pad(KlasifikasiTelur::count() + 1, 3, '0', STR_PAD_LEFT);
        return view('klasifikasi.create', compact('nextKode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_telur' => ['required', 'string'],
            'tanggal_panen' => ['required', 'date'],
            'berat' => ['required', 'numeric', 'min:10', 'max:200'],
            'diameter' => ['required', 'numeric', 'min:1', 'max:15'],
            'kondisi_cangkang' => ['required', 'in:Normal,Retak'],
            'warna_cangkang' => ['required', 'in:Cokelat Tua,Cokelat Muda'],
            'catatan' => ['nullable', 'string', 'max:255'],
        ]);

        // C4.5 Prediction
        $res = $this->c45Service->classify(
            (float) $validated['berat'],
            (float) $validated['diameter'],
            $validated['kondisi_cangkang'],
            $validated['warna_cangkang']
        );

        $klasifikasi = KlasifikasiTelur::create([
            'kode_telur' => $validated['kode_telur'],
            'tanggal_panen' => $validated['tanggal_panen'],
            'berat' => $validated['berat'],
            'diameter' => $validated['diameter'],
            'kondisi_cangkang' => $validated['kondisi_cangkang'],
            'warna_cangkang' => $validated['warna_cangkang'],
            'hasil_klasifikasi' => $res['label'],
            'rule_applied' => $res['rule_applied'],
            'pekerja_id' => Auth::id(),
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('klasifikasi.show', $klasifikasi->id)
            ->with('success', 'Telur berhasil diklasifikasikan sebagai: ' . $res['label']);
    }

    public function show($id)
    {
        $item = KlasifikasiTelur::with('pekerja')->findOrFail($id);
        return view('klasifikasi.show', compact('item'));
    }

    public function destroy($id)
    {
        $item = KlasifikasiTelur::findOrFail($id);
        $item->delete();

        return redirect()->route('klasifikasi.index')
            ->with('success', 'Data klasifikasi berhasil dihapus.');
    }
}
