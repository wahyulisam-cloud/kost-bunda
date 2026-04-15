<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenghuniController extends Controller
{
    // 1️⃣ Tampilkan semua penghuni
    public function index()
{
    $penghuni = DB::table('penghuni')
        ->leftJoin('kamar', 'penghuni.id_kamar', '=', 'kamar.id_kamar')
        ->select('penghuni.*', 'kamar.nomor_kamar', 'kamar.harga_sewa')
        ->paginate(10);

    return view('penghuni.index', compact('penghuni'));
}

    public function apiIndex()
{
    $data = DB::table('penghuni')
        ->join('kamar', 'penghuni.id_kamar', '=', 'kamar.id_kamar')
        ->select(
            'penghuni.*',
            'kamar.nomor_kamar'
        )
        ->get();

    return response()->json($data);
}

    public function apiKamar()
{
    return response()->json(
        DB::table('kamar')
            ->select('id_kamar', 'nomor_kamar', 'status') // ✅ tambahkan ini
            ->get()
    );
}

    public function apiUpdate(Request $request, $id)
{
    $request->validate([
        'nama_penghuni' => 'required',
        'NIK' => 'required',
        'no_hp' => 'required',
        'id_kamar' => 'required',
        'tanggal_masuk' => 'required',
        'status' => 'required'
    ]);

    DB::transaction(function () use ($request, $id) {

        $penghuni = DB::table('penghuni')
            ->where('id_penghuni', $id)
            ->first();

        $oldKamar = $penghuni->id_kamar;

        DB::table('penghuni')
            ->where('id_penghuni', $id)
            ->update([
                'nama_penghuni' => $request->nama_penghuni,
                'NIK' => $request->NIK,
                'no_hp' => $request->no_hp,
                'id_kamar' => $request->id_kamar,
                'tanggal_masuk' => $request->tanggal_masuk,
                'status' => $request->status,
            ]);

        if ($request->status == 'keluar') {
            DB::table('kamar')
                ->where('id_kamar', $oldKamar)
                ->update(['status' => 'kosong']);
            return;
        }

        if ($oldKamar != $request->id_kamar) {

            DB::table('kamar')
                ->where('id_kamar', $oldKamar)
                ->update(['status' => 'kosong']);

            DB::table('kamar')
                ->where('id_kamar', $request->id_kamar)
                ->update(['status' => 'terisi']);
        }
    });

    return response()->json([
        'status' => 'success',
        'message' => 'Data berhasil diupdate'
    ]);
}

    public function apiDelete($id)
{
    DB::transaction(function () use ($id) {

        $penghuni = DB::table('penghuni')
            ->where('id_penghuni', $id)
            ->first();

        if (!$penghuni) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        DB::table('kamar')
            ->where('id_kamar', $penghuni->id_kamar)
            ->update(['status' => 'kosong']);

        DB::table('penghuni')
            ->where('id_penghuni', $id)
            ->delete();
    });

    return response()->json([
        'status' => 'success',
        'message' => 'Data berhasil dihapus'
    ]);
}

    public function apiSearch(Request $request)
{
    $keyword = $request->keyword;

    $data = DB::table('penghuni')
        ->join('kamar', 'penghuni.id_kamar', '=', 'kamar.id_kamar')
        ->where('nama_penghuni', 'like', "%$keyword%")
        ->orWhere('NIK', 'like', "%$keyword%")
        ->select(
            'penghuni.*',
            'kamar.nomor_kamar'
        )
        ->get();

    return response()->json($data);
}

    public function apiStore(Request $request)
{
    $request->validate([
        'nama_penghuni' => 'required',
        'NIK' => 'required',
        'no_hp' => 'required',
        'id_kamar' => 'required',
        'tanggal_masuk' => 'required',
        'status' => 'required'
    ]);

    DB::transaction(function () use ($request) {

        DB::table('penghuni')->insert([
            'nama_penghuni' => $request->nama_penghuni,
            'NIK' => $request->NIK,
            'no_hp' => $request->no_hp,
            'id_kamar' => $request->id_kamar,
            'tanggal_masuk' => $request->tanggal_masuk,
            'status' => $request->status,
            'created_at' => now(),
        ]);

        if ($request->status == 'aktif') {
            DB::table('kamar')
                ->where('id_kamar', $request->id_kamar)
                ->update(['status' => 'terisi']);
        }
    });

    return response()->json([
        'status' => 'success',
        'message' => 'Data berhasil ditambahkan'
    ]);
}

    public function create()
    {
        $kamar = DB::table('kamar')
            ->where('status', 'kosong')
            ->get();

        return view('penghuni.create', compact('kamar'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_penghuni' => 'required|string|max:100',
            'NIK'           => 'required|string|max:20|unique:penghuni,NIK',
            'no_hp'         => 'required|string|max:15',
            'id_kamar'      => 'required|integer',
            'tanggal_masuk' => 'required|date',
            'status'        => 'required|in:aktif,keluar',
        ]);

        DB::transaction(function () use ($request) {

            DB::table('penghuni')->insert([
                'nama_penghuni' => $request->nama_penghuni,
                'NIK'           => $request->NIK,
                'no_hp'         => $request->no_hp,
                'id_kamar'      => $request->id_kamar,
                'tanggal_masuk' => $request->tanggal_masuk,
                'status'        => $request->status,
                'created_at'    => now(),
            ]);

            if ($request->status == 'aktif') {
                DB::table('kamar')
                    ->where('id_kamar', $request->id_kamar)
                    ->update(['status' => 'terisi']);
            }
        });

        return redirect()->route('penghuni.index')
            ->with('success', 'Penghuni berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $penghuni = DB::table('penghuni')
            ->where('id_penghuni', $id)
            ->first();

        $kamar = DB::table('kamar')
            ->where('status', 'kosong')
            ->orWhere('id_kamar', $penghuni->id_kamar)
            ->get();

        return view('penghuni.edit', compact('penghuni', 'kamar'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'nama_penghuni' => 'required|string|max:100',
        'NIK'           => 'required|string|max:20',
        'no_hp'         => 'required|string|max:15',
        'id_kamar'      => 'required|integer',
        'tanggal_masuk' => 'required|date',
        'status'        => 'required|in:aktif,keluar',
    ]);

    DB::transaction(function () use ($request, $id) {

        $penghuni = \App\Models\Penghuni::findOrFail($id);

        $oldKamar = $penghuni->id_kamar;

        $penghuni->update([
            'nama_penghuni' => $request->nama_penghuni,
            'NIK'           => $request->NIK,
            'no_hp'         => $request->no_hp,
            'id_kamar'      => $request->id_kamar,
            'tanggal_masuk' => $request->tanggal_masuk,
            'status'        => $request->status,
        ]);

        if ($request->status == 'keluar') {

            DB::table('kamar')
                ->where('id_kamar', $oldKamar)
                ->update(['status' => 'kosong']);

            return;
        }

        if ($oldKamar != $request->id_kamar) {

            DB::table('kamar')
                ->where('id_kamar', $oldKamar)
                ->update(['status' => 'kosong']);

            DB::table('kamar')
                ->where('id_kamar', $request->id_kamar)
                ->update(['status' => 'terisi']);

        } else {

            DB::table('kamar')
                ->where('id_kamar', $request->id_kamar)
                ->update(['status' => 'terisi']);
        }

    });

    return redirect()->route('penghuni.index')
        ->with('success', 'Data penghuni berhasil diperbarui!');
}

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {

            $kamarId = DB::table('penghuni')
                ->where('id_penghuni', $id)
                ->value('id_kamar');

            DB::table('penghuni')
                ->where('id_penghuni', $id)
                ->delete();

            DB::table('kamar')
                ->where('id_kamar', $kamarId)
                ->update(['status' => 'kosong']);
        });

        return redirect()->route('penghuni.index')
            ->with('success', 'Penghuni berhasil dihapus!');
    }
}