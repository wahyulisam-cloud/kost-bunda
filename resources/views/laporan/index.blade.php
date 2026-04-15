@extends('layouts.app')

@section('page-title', 'Laporan Keuangan Bulanan')

@section('content')

<style>
.page-title {
    color: #1e3a5f;
    font-weight: 700;
}

.card {
    border-radius: 20px;
}

.table thead {
    background: linear-gradient(90deg, #e8eddc, #dbe7c9);
    color: #1e3a5f;
    font-weight: 600;
}

.table tbody tr:hover {
    background: #f9f7ef;
    transition: 0.2s ease;
}

.nominal {
    font-weight: 600;
    font-variant-numeric: tabular-nums;
    letter-spacing: 0.5px;
}

.nominal .rp {
    font-size: 13px;
    margin-right: 6px;
    opacity: 0.7;
}

.nominal .angka {
    font-size: 15px;
    font-weight: 600;
}

.pemasukan {
    color: #2e7d32;
}

.pengeluaran {
    color: #c62828;
}

.text-jumlah {
    color: #1e3a5f;
}

.text-saldo-positive {
    color: #1b5e20;
}

.text-saldo-negative {
    color: #b71c1c;
}

.row-jumlah {
    background: #f4f1e6;
    font-weight: 600;
    border-top: 2px solid #dbe7c9;
}

.row-saldo {
    background: linear-gradient(90deg, #e3e9d5, #dbe7c9);
    font-size: 18px;
    font-weight: bold;
    border-top: 2px solid #cdd8b6;
}

.row-saldo-awal{
    background:#eef3e1;
    font-weight:600;
}

.form-control {
    border-radius: 12px;
}

.btn-premium {
    border-radius: 12px;
    font-weight: 600;
}

.btn-navy-gradient {
    background: linear-gradient(90deg, #1e3a5f, #27496d);
    color: white;
    border: none;
}

.btn-navy-gradient:hover {
    opacity: 0.9;
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="page-title">Laporan Bulanan</h4>
</div>

<div class="container mt-4">

<!-- FILTER -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <form method="GET">
            <div class="row align-items-end g-4">
                <div class="col-md-4">
                    <label class="fw-semibold mb-1">Pilih Bulan</label>
                    <input type="month"
                           name="bulan"
                           value="{{ $bulan }}"
                           class="form-control">
                </div>
                <div class="col-md-4">
                    <button class="btn btn-premium btn-navy-gradient w-100">
                        Tampilkan Laporan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- SEARCH -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body p-3">
        <input 
            type="text" 
            id="searchInput"
            class="form-control"
            placeholder="Cari tanggal / keterangan / nominal..."
        >
    </div>
</div>

<!-- TABEL -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">

            <table class="table mb-0 align-middle">

                <thead class="text-center">
                    <tr>
                        <th width="140">Tanggal</th>
                        <th>Keterangan</th>
                        <th width="180">Pemasukan</th>
                        <th width="180">Pengeluaran</th>
                    </tr>
                </thead>

                <tbody id="cashflowTable">

                    <!-- SALDO AWAL -->
                    <tr class="no-search row-saldo-awal">
                        <td class="text-center">-</td>
                        <td class="fw-semibold" style="color:#1e3a5f;">
                            Saldo Awal Bulan
                        </td>
                        <td class="text-end">-</td>
                        <td class="text-end nominal {{ $saldoAwal >= 0 ? 'text-saldo-positive' : 'text-saldo-negative' }}">
                            <span class="rp">Rp</span>
                            <span class="angka">
                                {{ number_format($saldoAwal,0,',','.') }}
                            </span>
                        </td>
                    </tr>

                    @forelse($data as $row)
                        <tr>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($row['tanggal'])->format('d/m/Y') }}
                            </td>

                            <td class="fw-semibold" style="color:#1e3a5f;">
                                {{ $row['keterangan'] }}
                            </td>

                            <td class="text-end nominal pemasukan">
                                @if($row['pemasukan'])
                                    <span class="rp">Rp</span>
                                    <span class="angka">
                                        {{ number_format($row['pemasukan'],0,',','.') }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>

                            <td class="text-end nominal pengeluaran">
                                @if($row['pengeluaran'])
                                    <span class="rp">Rp</span>
                                    <span class="angka">
                                        {{ number_format($row['pengeluaran'],0,',','.') }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4" style="color:#64748B;">
                                Tidak ada data bulan ini
                            </td>
                        </tr>
                    @endforelse

                    <!-- JUMLAH -->
                    <tr class="no-search row-jumlah">
                        <td colspan="2" style="border-left:5px solid #1e3a5f;">
                            Jumlah
                        </td>
                        <td class="text-end nominal text-jumlah">
                            <span class="rp">Rp</span>
                            <span class="angka">
                                {{ number_format($totalPemasukan,0,',','.') }}
                            </span>
                        </td>
                        <td class="text-end nominal text-jumlah">
                            <span class="rp">Rp</span>
                            <span class="angka">
                                {{ number_format($totalPengeluaran,0,',','.') }}
                            </span>
                        </td>
                    </tr>

                    <!-- SALDO AKHIR -->
                    <tr class="no-search row-saldo">
                        <td colspan="3" style="border-left:6px solid {{ $saldo >= 0 ? '#2e7d32' : '#c62828' }};">
                            Total Saldo
                        </td>
                        <td class="text-end nominal {{ $saldo >= 0 ? 'text-saldo-positive' : 'text-saldo-negative' }}">
                            <span class="rp">Rp</span>
                            <span class="angka">
                                {{ number_format($saldo,0,',','.') }}
                            </span>
                        </td>
                    </tr>

                </tbody>
            </table>

        </div>
    </div>
</div>
```

</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    let keyword = this.value.toLowerCase();
    let rows = document.querySelectorAll('#cashflowTable tr');

    rows.forEach(row => {
        if(row.classList.contains('no-search')) return;
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(keyword) ? '' : 'none';
    });
});
</script>

@endsection
