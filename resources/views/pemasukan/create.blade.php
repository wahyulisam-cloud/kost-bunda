@extends('layouts.app')

@section('page-title', 'Tambah Pemasukan')

@section('content')

<div class="container mt-4">

    <div class="card border-0 shadow-sm"
         style="border-radius:20px; background:#ffffff;">

        {{-- HEADER --}}
        <div class="card-header text-white"
             style="background:#1e3a5f; border-radius:20px 20px 0 0;">
            <h5 class="mb-0">Tambah Data Pemasukan</h5>
        </div>

        <div class="card-body p-4">

            {{-- ERROR VALIDATION --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ERROR CUSTOM --}}
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            {{-- SUCCESS --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('pemasukan.store') }}" method="POST">
                @csrf

                <div class="row g-4">

                    {{-- Penghuni --}}
                    <div class="col-md-6">
                        <label class="fw-semibold mb-1">Penghuni</label>
                        <select name="id_penghuni"
                                id="penghuni"
                                class="form-control"
                                style="border-radius:12px;"
                                required>

                            <option value="">-- Pilih Penghuni --</option>

                            @foreach($penghuni as $p)
                                @php
                                    $bulanSudah = isset($pemasukan[$p->id_penghuni])
                                        ? $pemasukan[$p->id_penghuni]->pluck('bulan')->toArray()
                                        : [];
                                @endphp

                                <option value="{{ $p->id_penghuni }}"
                                        data-tanggal="{{ $p->tanggal_masuk }}"
                                        data-bulan='@json($bulanSudah)'>
                                    {{ $p->nama_penghuni }}
                                    - Kamar {{ $p->kamar->nomor_kamar ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Bulan --}}
                    <div class="col-md-3">
                        <label class="fw-semibold mb-1">Bulan</label>
                        @php
                            $bulanList = [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ];
                        @endphp
                        <select name="bulan"
                                id="bulan"
                                class="form-control"
                                style="border-radius:12px;"
                                required>
                            @foreach($bulanList as $angka => $nama)
                                <option value="{{ $angka }}">
                                    {{ $nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tahun --}}
                    <div class="col-md-3">
                        <label class="fw-semibold mb-1">Tahun</label>
                        <input type="number"
                               name="tahun"
                               id="tahun"
                               class="form-control"
                               style="border-radius:12px;"
                               value="{{ date('Y') }}"
                               required>
                    </div>

                    {{-- Tanggal Bayar --}}
                    <div class="col-md-6">
                        <label class="fw-semibold mb-1">Tanggal Bayar</label>
                        <input type="date"
                               name="tanggal_bayar"
                               id="tanggal_bayar"
                               class="form-control"
                               style="border-radius:12px;"
                               required>
                    </div>

                    {{-- STATUS AUTO --}}
                    <input type="hidden" name="status" value="belum">

                </div>

                {{-- BUTTON --}}
                <div class="text-end mt-4">

                    <a href="{{ route('pemasukan.index') }}"
                       class="btn btn-light px-4"
                       style="border-radius:10px;">
                        Batal
                    </a>

                    <button type="submit"
                            class="btn btn-premium btn-navy-gradient">
                        Simpan
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>

{{-- STYLE OPTION DISABLED --}}
<style>
option:disabled {
    color: #ccc;
}
</style>

{{-- SCRIPT --}}
<script>

function updateForm(autoSelect = false) {

    let penghuni = document.getElementById('penghuni');
    let selected = penghuni.options[penghuni.selectedIndex];

    let tanggalMasuk = selected.getAttribute('data-tanggal');
    let bulanSudah = selected.getAttribute('data-bulan');

    let bulanSelect = document.getElementById('bulan');
    let tahun = document.getElementById('tahun').value;

    // reset disable bulan
    for (let i = 0; i < bulanSelect.options.length; i++) {
        bulanSelect.options[i].disabled = false;
    }

    // disable bulan yang sudah dibayar
    if (bulanSudah) {

        let bulanArray = JSON.parse(bulanSudah);

        bulanArray.forEach(function (b) {

            for (let i = 0; i < bulanSelect.options.length; i++) {

                if (parseInt(bulanSelect.options[i].value) === parseInt(b)) {
                    bulanSelect.options[i].disabled = true;
                }

            }

        });

    }

    // auto pilih bulan pertama yang tersedia hanya saat penghuni berubah
    if (autoSelect) {

        for (let i = 0; i < bulanSelect.options.length; i++) {

            if (!bulanSelect.options[i].disabled) {
                bulanSelect.selectedIndex = i;
                break;
            }

        }

    }

    // update tanggal bayar otomatis
    let bulan = bulanSelect.value;

    if (tanggalMasuk && bulan && tahun) {

        let tgl = new Date(tanggalMasuk).getDate();

        let tanggalBaru = `${tahun}-${String(bulan).padStart(2,'0')}-${String(tgl).padStart(2,'0')}`;

        document.getElementById('tanggal_bayar').value = tanggalBaru;

    }

}

// event saat pilih penghuni
document.getElementById('penghuni').addEventListener('change', function () {
    updateForm(true);
});

// event saat bulan berubah
document.getElementById('bulan').addEventListener('change', function () {
    updateForm(false);
});

// event saat tahun berubah
document.getElementById('tahun').addEventListener('input', function () {
    updateForm(false);
});

</script>

@endsection