<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengeluaran;
use App\Models\KategoriPengeluaran;

class PengeluaranController extends Controller
{
    // =====================
    // HALAMAN UTAMA
    // =====================
    public function index()
    {
        $pengeluaran = Pengeluaran::with('kategori')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('pengeluaran.index', compact('pengeluaran'));
    }
    
    public function apiIndex()
{
    return response()->json(\App\Models\Pengeluaran::all());
}

public function apiStore(Request $request)
{
    $request->validate([
        'tanggal'        => 'required|date',
        'tanggal_bayar'  => 'nullable|date',
        'id_kategori'    => 'required|exists:kategori_pengeluaran,id_kategori',
        'nominal'        => 'required|numeric|min:1',
        'keterangan'     => 'nullable|string|max:255',
    ]);

    $kategori = KategoriPengeluaran::findOrFail($request->id_kategori);

    if (strtolower($kategori->nama_kategori) === 'lainnya') {
        if (!$request->filled('keterangan')) {
            return response()->json([
                'message' => 'Keterangan wajib diisi jika memilih Lainnya'
            ], 422);
        }

        $keteranganFinal = $request->keterangan;
    } else {
        $keteranganFinal = $request->filled('keterangan')
            ? $request->keterangan
            : $kategori->nama_kategori;
    }

    $data = Pengeluaran::create([
        'tanggal'        => $request->tanggal,
        'tanggal_bayar'  => $request->tanggal_bayar,
        'id_kategori'    => $request->id_kategori,
        'nominal'        => $request->nominal,
        'keterangan'     => $keteranganFinal,
        'status'         => $request->tanggal_bayar ? 'lunas' : 'belum',
    ]);

    return response()->json([
        'message' => 'Data berhasil disimpan',
        'data' => $data
    ], 201);
}

    // =====================
    // FORM TAMBAH
    // =====================
    public function create()
    {
        $kategori = KategoriPengeluaran::orderBy('nama_kategori')->get();
        return view('pengeluaran.create', compact('kategori'));
    }

    // =====================
    // SIMPAN DATA (FINAL LOGIC)
    // =====================
    public function store(Request $request)
    {
        $request->validate([
            'tanggal'        => 'required|date',
            'tanggal_bayar'  => 'nullable|date',
            'id_kategori'    => 'required|exists:kategori_pengeluaran,id_kategori',
            'nominal'        => 'required|numeric|min:1',
            'keterangan'     => 'nullable|string|max:255',
        ]);

        // ambil kategori
        $kategori = KategoriPengeluaran::findOrFail($request->id_kategori);

        // =====================
        // LOGIKA UTAMA
        // =====================
        if (strtolower($kategori->nama_kategori) === 'lainnya') {

            // 🔴 jika pilih lainnya → WAJIB isi keterangan
            if (!$request->filled('keterangan')) {
                return back()
                    ->withErrors(['keterangan' => 'Keterangan wajib diisi jika memilih Lainnya'])
                    ->withInput();
            }

            $keteranganFinal = $request->keterangan;

        } else {

    $keteranganFinal = $request->filled('keterangan')
        ? $request->keterangan
        : $kategori->nama_kategori;


        }

        Pengeluaran::create([
            'tanggal'        => $request->tanggal,
            'tanggal_bayar'  => $request->tanggal_bayar,
            'id_kategori'    => $request->id_kategori,
            'nominal'        => $request->nominal,
            'keterangan'     => $keteranganFinal, // 🔥 PASTI TIDAK NULL
            'status'         => $request->tanggal_bayar ? 'lunas' : 'belum',
        ]);

        return redirect()->route('pengeluaran.index')
            ->with('success', 'Pengeluaran berhasil ditambahkan');
    }

    // =====================
    // SEARCH AJAX
    // =====================
    public function search(Request $request)
    {
        $search = $request->search;

        $pengeluaran = Pengeluaran::with('kategori')
            ->when($search, function ($query) use ($search) {
                $query->where('keterangan', 'like', "%{$search}%")
                      ->orWhere('tanggal', 'like', "%{$search}%")
                      ->orWhereHas('kategori', function ($q) use ($search) {
                          $q->where('nama_kategori', 'like', "%{$search}%");
                      });
            })
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('pengeluaran.partials.table', compact('pengeluaran'))->render();
    }

    // =====================
    // HAPUS
    // =====================
    public function destroy($id)
{
    $data = Pengeluaran::find($id);

    if (!$data) {
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    $data->delete();

    return response()->json(['message' => 'Data berhasil dihapus']);
}
}
