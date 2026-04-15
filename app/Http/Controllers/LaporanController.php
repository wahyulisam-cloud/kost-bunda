<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->bulan ?? date('Y-m');
        $tanggal = Carbon::parse($bulan);

        $data = [];

        // =====================
        // PEMASUKAN BULAN INI
        // =====================
        $pemasukan = Pemasukan::whereYear('tanggal_bayar', $tanggal->year)
            ->whereMonth('tanggal_bayar', $tanggal->month)
            ->get();

        foreach ($pemasukan as $p) {
            $data[] = [
                'tanggal' => $p->tanggal_bayar,
                'keterangan' => 'Pembayaran Kost',
                'pemasukan' => $p->nominal,
                'pengeluaran' => null
            ];
        }

        // =====================
        // PENGELUARAN BULAN INI
        // =====================
        $pengeluaran = Pengeluaran::whereYear('tanggal', $tanggal->year)
            ->whereMonth('tanggal', $tanggal->month)
            ->get();

        foreach ($pengeluaran as $p) {
            $data[] = [
                'tanggal' => $p->tanggal,
                'keterangan' => $p->keterangan,
                'pemasukan' => null,
                'pengeluaran' => $p->nominal
            ];
        }

        // =====================
        // URUTKAN DATA
        // =====================
        usort($data, function ($a, $b) {
            return strtotime($a['tanggal']) - strtotime($b['tanggal']);
        });

        // =====================
        // TOTAL BULAN INI
        // =====================
        $totalPemasukan = $pemasukan->sum('nominal');
        $totalPengeluaran = $pengeluaran->sum('nominal');

        // =====================
        // SALDO SEBELUM BULAN INI
        // =====================
        $batasTanggal = $tanggal->copy()->startOfMonth();

        $saldoAwal =
            Pemasukan::whereDate('tanggal_bayar', '<', $batasTanggal)->sum('nominal')
            -
            Pengeluaran::whereDate('tanggal', '<', $batasTanggal)->sum('nominal');

        // jika saldo positif jangan dibawa
        if ($saldoAwal > 0) {
            $saldoAwal = 0;
        }

        // =====================
        // SALDO AKHIR
        // =====================
        $saldo = $saldoAwal + $totalPemasukan - $totalPengeluaran;

        return view('laporan.index', compact(
            'bulan',
            'data',
            'totalPemasukan',
            'totalPengeluaran',
            'saldo',
            'saldoAwal'
        ));
    }


    // =====================
    // API LAPORAN
    // =====================
    public function apiIndex(Request $request)
    {
        $bulan = $request->bulan ?? now()->format('Y-m');
        $tanggal = Carbon::parse($bulan);

        // PEMASUKAN
        $pemasukan = Pemasukan::with('penghuni.kamar')
            ->whereYear('tanggal_bayar', $tanggal->year)
            ->whereMonth('tanggal_bayar', $tanggal->month)
            ->get()
            ->map(function ($item) {

                $namaPenghuni = $item->penghuni->nama ?? 'Penghuni';
                $namaKamar = $item->penghuni->kamar->nama_kamar ?? '';

                return [
                    'tanggal' => $item->tanggal_bayar,
                    'keterangan' => "Sewa {$namaKamar} — {$namaPenghuni}",
                    'pemasukan' => $item->nominal,
                    'pengeluaran' => null
                ];
            });

        // PENGELUARAN
        $pengeluaran = Pengeluaran::whereYear('tanggal', $tanggal->year)
            ->whereMonth('tanggal', $tanggal->month)
            ->get()
            ->map(function ($item) {
                return [
                    'tanggal' => $item->tanggal,
                    'keterangan' => $item->keterangan,
                    'pemasukan' => null,
                    'pengeluaran' => $item->nominal
                ];
            });

        // GABUNG
        $data = $pemasukan
            ->concat($pengeluaran)
            ->sortBy('tanggal')
            ->values();

        $totalPemasukan = $pemasukan->sum('pemasukan');
        $totalPengeluaran = $pengeluaran->sum('pengeluaran');

        // SALDO SEBELUM BULAN INI
        $batasTanggal = $tanggal->copy()->startOfMonth();

        $saldoAwal =
            Pemasukan::whereDate('tanggal_bayar', '<', $batasTanggal)->sum('nominal')
            -
            Pengeluaran::whereDate('tanggal', '<', $batasTanggal)->sum('nominal');

        if ($saldoAwal > 0) {
            $saldoAwal = 0;
        }

        $saldo = $saldoAwal + $totalPemasukan - $totalPengeluaran;

        return response()->json([
            'status' => 'success',
            'bulan' => $bulan,
            'saldo_awal' => $saldoAwal,
            'total_pemasukan' => $totalPemasukan,
            'total_pengeluaran' => $totalPengeluaran,
            'saldo' => $saldo,
            'data' => $data
        ]);
    }
}