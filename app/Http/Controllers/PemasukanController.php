<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemasukan;
use App\Models\Penghuni;
use Illuminate\Support\Facades\DB;

class PemasukanController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

public function index(Request $request)
{
    $search = $request->search;

    $pemasukan = Pemasukan::with('penghuni.kamar')
        ->when($search, function ($query) use ($search) {

            $query->where(function ($q) use ($search) {

                // 🔍 Cari dari nama penghuni
                $q->whereHas('penghuni', function ($qp) use ($search) {
                    $qp->where('nama_penghuni', 'like', '%' . $search . '%');
                });

                // 🔍 Cari dari nomor kamar
                $q->orWhereHas('penghuni.kamar', function ($qk) use ($search) {
                    $qk->where('nomor_kamar', 'like', '%' . $search . '%');
                });

                // 🔍 Cari dari status
                $q->orWhere('status', 'like', '%' . $search . '%');

                // 🔍 Cari dari tahun
                $q->orWhere('tahun', 'like', '%' . $search . '%');

            });

        })
        ->latest()
        ->paginate(10)
        ->withQueryString(); // 🔥 biar search tidak hilang saat pagination

    return view('pemasukan.index', compact('pemasukan'));
}
    public function apiIndex()
{
    $pemasukan = Pemasukan::with('penghuni.kamar')
        ->latest()
        ->get();

    return response()->json($pemasukan);
}

    public function apiStore(Request $request)
{
    $request->validate([
        'id_penghuni' => 'required|exists:penghuni,id_penghuni',
        'bulan' => 'required|integer|min:1|max:12',
        'tahun' => 'required',
        'tanggal_bayar' => 'required|date',
        'nominal' => 'required',
        'status' => 'required'
    ]);

    $pemasukan = Pemasukan::create([
        'id_penghuni' => $request->id_penghuni,
        'bulan' => $request->bulan,
        'tahun' => $request->tahun,
        'tanggal_bayar' => $request->tanggal_bayar,
        'nominal' => $request->nominal,
        'status' => $request->status
    ]);

    return response()->json([
        "message" => "Data berhasil ditambahkan",
        "data" => $pemasukan
    ]);
}

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

   public function create()
{
    $penghuni = Penghuni::with('kamar')->get();

    // 🔥 ambil semua pemasukan
    $pemasukan = Pemasukan::select('id_penghuni', 'bulan')
        ->where('status', 'lunas')
        ->get()
        ->groupBy('id_penghuni');

    return view('pemasukan.create', compact('penghuni', 'pemasukan'));
}

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
{
    $request->validate([
        'id_penghuni' => 'required|exists:penghuni,id_penghuni',
        'bulan' => 'required|integer|min:1|max:12',
        'tahun' => 'required|integer',
        'tanggal_bayar' => 'required|date',
        'status' => 'required|in:lunas,belum',
    ]);

    // 🔴 CEK DULU SEBELUM TRANSACTION
    $sudahAda = Pemasukan::where('id_penghuni', $request->id_penghuni)
        ->where('bulan', $request->bulan)
        ->where('tahun', $request->tahun)
        ->exists();

    if ($sudahAda) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Pembayaran bulan ini sudah dilakukan!');
    }

    DB::transaction(function () use ($request) {

        $penghuni = Penghuni::with('kamar')
            ->findOrFail($request->id_penghuni);

        // 🔥 ambil harga otomatis
        $nominal = $penghuni->kamar->harga_sewa ?? 0;

        Pemasukan::create([
            'id_penghuni' => $request->id_penghuni,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'tanggal_bayar' => $request->tanggal_bayar,
            'nominal' => $nominal,
            'status' => 'belum',
        ]);
    });

    return redirect()->route('pemasukan.index')
        ->with('success', 'Pemasukan berhasil ditambahkan!');
}


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        Pemasukan::findOrFail($id)->delete();

        return back()->with('success','Data berhasil dihapus');
    }
}
