@extends('layouts.app')

@section('title', 'Pemasukan')

@section('content')

{{-- ALERT SUKSES --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold" style="color:#1e3a5f;">
        Data Pemasukan
    </h4>

    <a href="{{ route('pemasukan.create') }}"
       class="btn btn-premium btn-navy-gradient">
        + Tambah Pemasukan
    </a>
</div>

<div class="row mb-3">
    <div class="col-md-4">
        <input 
            type="text"
            id="search"
            class="form-control"
            placeholder="Cari nama / kamar..."
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
                        <th class="fw-bold" style="color:#1e3a5f;">Nama</th>
                        <th class="fw-bold" style="color:#1e3a5f;">Kamar</th>
                        <th class="fw-bold" style="color:#1e3a5f;">Bulan</th>
                        <th class="fw-bold" style="color:#1e3a5f;">Tahun</th>
                        <th class="fw-bold" style="color:#1e3a5f;">Nominal</th>
                        <th class="fw-bold" style="color:#1e3a5f;">Status</th>
                        <th width="140" class="fw-semibold text-center" style="color:#1e3a5f;">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody id="pemasukanTable">
                    @include('pemasukan.partials.table')
                </tbody>

            </table>

        </div>
    </div>
</div>


{{-- PAGINATION --}}
<div class="d-flex justify-content-end mt-3">
    {{ $pemasukan->links() }}
</div>

{{-- SCRIPT REALTIME SEARCH --}}
<script>
let timeout = null;

document.getElementById('search').addEventListener('keyup', function () {
    clearTimeout(timeout);

    let keyword = this.value;

    timeout = setTimeout(function () {

        fetch(`{{ route('pemasukan.index') }}?search=${keyword}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {

            // parsing HTML
            let parser = new DOMParser();
            let doc = parser.parseFromString(html, 'text/html');

            // ambil isi tbody dari response
            let newTable = doc.querySelector('#pemasukanTable').innerHTML;

            // replace tanpa reload
            document.querySelector('#pemasukanTable').innerHTML = newTable;

        });

    }, 400);

});
</script>

@endsection
