@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- ================= HERO FULL WIDTH ================= --}}
<div class="container-fluid px-0 mb-5">
    <div class="position-relative"
         style="
            background-image: url('https://kost-bunda-production.up.railway.app/foto_user/rumah.jpg');
            background-size: cover;
            background-position: center;
            height: 450px;
            overflow: hidden;
         ">

        {{-- Overlay --}}
        <div class="position-absolute top-0 start-0 w-100 h-100"
             style="
                background: linear-gradient(
                    180deg,
                    rgba(0,0,0,0.50) 0%,
                    rgba(0,0,0,0.40) 50%,
                    rgba(0,0,0,0.60) 100%
                );
             ">
        </div>

        {{-- Text --}}
        <div class="position-absolute top-50 start-50 translate-middle text-center text-white px-3">
            <h1 class="fw-bold display-4 mb-2" style="letter-spacing:1px;">
                WELCOME TO KOST BUNDA
            </h1>
            <p class="mb-0" style="font-size:18px; opacity:0.85;">
                Sistem Manajemen Keuangan Kost
            </p>
        </div>

    </div>
</div>


{{-- ================= CONTENT AREA ================= --}}
<div class="container">

    {{-- ===== CARD STATISTIK ===== --}}
    <div class="row mb-4 g-3">

        <div class="col-md-3">
            <div class="p-3 d-flex align-items-center gap-3 shadow-sm rounded bg-white">
                <i class="bi bi-cash-stack fs-2 text-primary"></i>
                <div>
                    <small class="text-uppercase fw-semibold text-muted">
                        Pemasukan Bulan Ini
                    </small>
                    <h5 class="mb-0 fw-bold">
                        Rp {{ number_format($pemasukan,0,',','.') }}
                    </h5>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="p-3 d-flex align-items-center gap-3 shadow-sm rounded bg-white">
                <i class="bi bi-credit-card-2-back fs-2 text-danger"></i>
                <div>
                    <small class="text-uppercase fw-semibold text-muted">
                        Pengeluaran Bulan Ini
                    </small>
                    <h5 class="mb-0 fw-bold">
                        Rp {{ number_format($pengeluaran,0,',','.') }}
                    </h5>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="p-3 d-flex align-items-center gap-3 shadow-sm rounded bg-white">
                <i class="bi bi-bar-chart-line fs-2 text-success"></i>
                <div>
                    <small class="text-uppercase fw-semibold text-muted">
                        Profit
                    </small>
                    <h5 class="mb-0 fw-bold">
                        Rp {{ number_format($profit,0,',','.') }}
                    </h5>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="p-3 d-flex align-items-center gap-3 shadow-sm rounded bg-white">
                <i class="bi bi-house-door fs-2 text-warning"></i>
                <div>
                    <small class="text-uppercase fw-semibold text-muted">
                        Kamar Tersedia
                    </small>
                    <h5 class="mb-0 fw-bold">
                        {{ $kamarTersedia }} Kamar
                    </h5>
                </div>
            </div>
        </div>

    </div>


    {{-- ===== CARD GRAFIK ===== --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">
                    Grafik Pemasukan vs Pengeluaran
                </h5>

                <select id="tahunSelect" class="form-select w-auto">
                    @foreach($listTahun as $th)
                        <option value="{{ $th }}" {{ $th == $tahun ? 'selected' : '' }}>
                            {{ $th }}
                        </option>
                    @endforeach
                </select>
            </div>

            <canvas id="keuanganChart" height="110"></canvas>

        </div>
    </div>

</div> {{-- END CONTAINER --}}


{{-- ================= CHART JS ================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('keuanganChart');

let chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($grafik['labels']),
        datasets: [
            {
                label: 'Pemasukan',
                data: @json($grafik['pemasukan']),
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59,130,246,0.08)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointRadius: 4
            },
            {
                label: 'Pengeluaran',
                data: @json($grafik['pengeluaran']),
                borderColor: '#EF4444',
                backgroundColor: 'rgba(239,68,68,0.08)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointRadius: 4
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    font: { weight: '600' }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#E2E8F0' }
            },
            x: {
                grid: { color: '#F1F5F9' }
            }
        }
    }
});


// ===== AJAX GANTI TAHUN =====
document.getElementById('tahunSelect').addEventListener('change', function () {
    const tahun = this.value;

    fetch(`/dashboard/grafik/${tahun}`)
        .then(res => res.json())
        .then(data => {
            chart.data.labels = data.labels;
            chart.data.datasets[0].data = data.pemasukan;
            chart.data.datasets[1].data = data.pengeluaran;
            chart.update();
        });
});
</script>

@endsection
