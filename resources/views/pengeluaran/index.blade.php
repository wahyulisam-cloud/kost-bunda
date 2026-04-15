@extends('layouts.app')

@section('content')
<div class="container mt-4">

{{-- ALERT SUKSES --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold" style="color:#1e3a5f;">
        Data Pengeluaran
    </h4>

    <a href="{{ route('pengeluaran.create') }}"
       class="btn btn-premium btn-navy-gradient">
        + Tambah Pengeluaran
    </a>
</div>

            {{-- SEARCH --}}
<div class="row mb-3">
    <div class="col-md-4">
        <input 
            type="text"
            id="search"
            class="form-control"
            placeholder="Cari Pengeluaran..."
        >
    </div>
</div>

           {{-- CARD TABEL --}}
<div class="card border-0 shadow-sm"
     style="border-radius:20px; background:#ffffff;">

    <div class="card-body p-0">
        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead style="background:#f4f1e6;">
                    <tr class="text-center">
    <th class="fw-bold" style="color:#1e3a5f;">Jenis Pengeluaran</th>
    <th class="fw-bold" style="color:#1e3a5f;">Keterangan</th>
    <th class="fw-bold" style="color:#1e3a5f;">Tanggal Dicatat</th>
    <th class="fw-bold" style="color:#1e3a5f;">Tanggal Bayar</th>
    <th class="fw-bold" style="color:#1e3a5f;">Nominal</th>
    <th class="fw-bold" style="color:#1e3a5f;" width="120">Aksi</th>
</tr>
                    </thead>

                    <tbody id="pengeluaranTable">
                        @include('pengeluaran.partials.table')
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div> <!-- End Container -->

{{-- Script Pencarian --}}
<script>
document.getElementById('search').addEventListener('keyup', function () {
    fetch(`/pengeluaran/search?search=${encodeURIComponent(this.value)}`)
        .then(res => res.text())
        .then(html => document.getElementById('pengeluaranTable').innerHTML = html);
});
</script>
@endsection
