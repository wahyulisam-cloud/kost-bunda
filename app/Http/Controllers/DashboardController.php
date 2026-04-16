<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $tahun = date('Y');
        $bulan = date('m');

        // =======================
        // KARTU RINGKASAN
        // =======================
        $pemasukan = DB::table('pemasukan')
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->where('status', 'lunas')
            ->sum('nominal');

        $pengeluaran = DB::table('pengeluaran')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('status', 'lunas')
            ->sum('nominal');

        $profit = $pemasukan - $pengeluaran;

        $kamarTersedia = DB::table('kamar')
            ->where('status', 'kosong')
            ->count();

        // =======================
        // DROPDOWN TAHUN
        // =======================
        $listTahun = DB::table('pemasukan')
            ->select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // =======================
        // DATA GRAFIK DEFAULT
        // =======================
        $grafik = $this->ambilGrafik($tahun);

        dd('MASUK DASHBOARD');
        return view('dashboard.index', compact(
            'pemasukan',
            'pengeluaran',
            'profit',
            'kamarTersedia',
            'listTahun',
            'grafik',
            'tahun'
        ));
    }

    // =======================
    // AJAX ENDPOINT
    // =======================
    public function grafikByTahun($tahun)
    {
        return response()->json($this->ambilGrafik($tahun));
    }

    // =======================
    // FUNGSI INTI GRAFIK
    // =======================
    private function ambilGrafik($tahun)
    {
        $pemasukan = DB::table('pemasukan')
            ->select('bulan', DB::raw('SUM(nominal) as total'))
            ->where('tahun', $tahun)
            ->where('status', 'lunas')
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $pengeluaran = DB::table('pengeluaran')
            ->select(
                DB::raw('MONTH(tanggal) as bulan'),
                DB::raw('SUM(nominal) as total')
            )
            ->whereYear('tanggal', $tahun)
            ->where('status', 'lunas')
            ->groupBy(DB::raw('MONTH(tanggal)'))
            ->pluck('total', 'bulan');

        $dataPemasukan = [];
        $dataPengeluaran = [];

        for ($i = 1; $i <= 12; $i++) {
            $dataPemasukan[] = $pemasukan[$i] ?? 0;
            $dataPengeluaran[] = $pengeluaran[$i] ?? 0;
        }

        return [
            'labels' => ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
            'pemasukan' => $dataPemasukan,
            'pengeluaran' => $dataPengeluaran
        ];
    }
}
