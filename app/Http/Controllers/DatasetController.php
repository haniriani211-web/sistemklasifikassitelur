<?php

namespace App\Http\Controllers;

use App\Models\DatasetTelur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DatasetController extends Controller
{
    public function index(Request $request)
    {
        $query = DatasetTelur::query();

        if ($request->filled('kualitas')) {
            $query->where('kualitas', $request->kualitas);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('kode_telur', 'like', "%{$search}%");
        }

        $dataset = $query->paginate(20)->withQueryString();
        $totalCount = DatasetTelur::count();
        $layakCount = DatasetTelur::where('kualitas', 'Layak Jual')->count();
        $tidakLayakCount = DatasetTelur::where('kualitas', 'Tidak Layak Jual')->count();

        return view('dataset.index', compact('dataset', 'totalCount', 'layakCount', 'tidakLayakCount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_telur' => ['required', 'string', 'unique:dataset_telur,kode_telur'],
            'berat' => ['required', 'numeric', 'min:10', 'max:200'],
            'diameter' => ['required', 'numeric', 'min:1', 'max:15'],
            'kondisi_cangkang' => ['required', 'in:Normal,Retak'],
            'warna_cangkang' => ['required', 'in:Cokelat Tua,Cokelat Muda'],
            'kualitas' => ['required', 'in:Layak Jual,Tidak Layak Jual'],
        ]);

        DatasetTelur::create($validated);

        return redirect()->route('dataset.index')
            ->with('success', 'Data latih baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $item = DatasetTelur::findOrFail($id);

        $validated = $request->validate([
            'kode_telur' => ['required', 'string', 'unique:dataset_telur,kode_telur,' . $id],
            'berat' => ['required', 'numeric', 'min:10', 'max:200'],
            'diameter' => ['required', 'numeric', 'min:1', 'max:15'],
            'kondisi_cangkang' => ['required', 'in:Normal,Retak'],
            'warna_cangkang' => ['required', 'in:Cokelat Tua,Cokelat Muda'],
            'kualitas' => ['required', 'in:Layak Jual,Tidak Layak Jual'],
        ]);

        $item->update($validated);

        return redirect()->route('dataset.index')
            ->with('success', 'Data latih berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = DatasetTelur::findOrFail($id);
        $item->delete();

        return redirect()->route('dataset.index')
            ->with('success', 'Data latih berhasil dihapus.');
    }

    public function reset()
    {
        DatasetTelur::truncate();
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);

        return redirect()->route('dataset.index')
            ->with('success', 'Dataset berhasil direset ke data latih awal (20 sampel dari RUMUS C4.5.xlsx).');
    }
}
