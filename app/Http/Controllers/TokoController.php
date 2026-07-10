<?php

namespace App\Http\Controllers;

use App\Models\Toko;
use Illuminate\Http\Request;

class TokoController extends Controller
{
    public function index(Request $request)
    {
        $query = Toko::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_toko', 'like', "%{$search}%")
                  ->orWhere('nama_toko', 'like', "%{$search}%");
            });
        }

        $tokoList = $query->orderBy('kode_toko')->paginate(20)->withQueryString();

        return view('toko.index', compact('tokoList'));
    }

    public function create()
    {
        return view('toko.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_toko' => 'required|string|max:50|unique:toko,kode_toko',
            'nama_toko' => 'required|string|max:255',
        ], [
            'kode_toko.required' => 'Kode Toko wajib diisi.',
            'kode_toko.unique'   => 'Kode Toko sudah digunakan.',
            'kode_toko.max'      => 'Kode Toko maksimal 50 karakter.',
            'nama_toko.required' => 'Nama Toko wajib diisi.',
            'nama_toko.max'      => 'Nama Toko maksimal 255 karakter.',
        ]);

        Toko::create([
            'kode_toko' => $request->kode_toko,
            'nama_toko' => $request->nama_toko,
            'status'    => 1,
        ]);

        return redirect()->route('toko.index')->with('success', 'Toko berhasil ditambahkan!');
    }

    public function edit(Toko $toko)
    {
        return view('toko.edit', compact('toko'));
    }

    public function update(Request $request, Toko $toko)
    {
        $request->validate([
            'kode_toko' => 'required|string|max:50|unique:toko,kode_toko,' . $toko->id,
            'nama_toko' => 'required|string|max:255',
        ], [
            'kode_toko.required' => 'Kode Toko wajib diisi.',
            'kode_toko.unique'   => 'Kode Toko sudah digunakan.',
            'kode_toko.max'      => 'Kode Toko maksimal 50 karakter.',
            'nama_toko.required' => 'Nama Toko wajib diisi.',
            'nama_toko.max'      => 'Nama Toko maksimal 255 karakter.',
        ]);

        $toko->update([
            'kode_toko' => $request->kode_toko,
            'nama_toko' => $request->nama_toko,
        ]);

        return redirect()->route('toko.index')->with('success', 'Toko berhasil diperbarui!');
    }

    public function destroy(Toko $toko)
    {
        // Check if toko has related kurang kirim records
        if ($toko->kurangKirim()->where('status', 1)->count() > 0) {
            return back()->with('error', 'Toko tidak bisa dihapus karena masih ada ' . $toko->kurangKirim()->where('status', 1)->count() . ' surat jalan yang terikat.');
        }

        $toko->delete();

        return redirect()->route('toko.index')->with('success', 'Toko berhasil dihapus!');
    }

    /**
     * Toggle status aktif/nonaktif toko.
     */
    public function toggleStatus(Toko $toko)
    {
        $toko->update(['status' => $toko->status ? 0 : 1]);

        $statusLabel = $toko->status ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Toko {$toko->nama_toko} berhasil {$statusLabel}!");
    }
}
