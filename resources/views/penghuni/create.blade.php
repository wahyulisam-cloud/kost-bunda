@extends('layouts.app')

@section('page-title', 'Tambah Penghuni')

@section('content')

<div class="container mt-4">

    <div class="card border-0 shadow-sm"
         style="border-radius:20px; background:#ffffff;">

        <div class="card-header text-white"
             style="background:#1e3a5f; border-radius:20px 20px 0 0;">
            <h5 class="mb-0">Tambah Penghuni Baru</h5>
        </div>

        <div class="card-body p-4">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('penghuni.store') }}" method="POST">
                @csrf

                <div class="row g-4">

                    <div class="col-md-6">
                        <label class="fw-semibold mb-1">Nama Penghuni</label>
                        <input type="text"
                               name="nama_penghuni"
                               class="form-control"
                               style="border-radius:12px;"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="fw-semibold mb-1">NIK</label>
                        <input type="text"
                               name="NIK"
                               class="form-control"
                               style="border-radius:12px;"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="fw-semibold mb-1">No HP</label>
                        <input type="text"
                               name="no_hp"
                               class="form-control"
                               style="border-radius:12px;"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="fw-semibold mb-1">Kamar</label>
                        <select name="id_kamar"
                                class="form-control"
                                style="border-radius:12px;"
                                required>
                            <option value="">-- Pilih Kamar --</option>
                            @foreach($kamar as $k)
                                <option value="{{ $k->id_kamar }}">
                                    {{ $k->nomor_kamar }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="fw-semibold mb-1">Tanggal Masuk</label>
                        <input type="date"
                               name="tanggal_masuk"
                               class="form-control"
                               style="border-radius:12px;"
                               required>
                    </div>

                  <div class="col-md-6">
    <label class="fw-semibold mb-1">Status</label>
    <select name="status"
            class="form-control"
            style="border-radius:12px;"
            disabled>
        <option value="aktif" selected>Aktif</option>
    </select>

    <!-- Tambahkan hidden input agar tetap terkirim -->
    <input type="hidden" name="status" value="aktif">
</div>

                <div class="text-end mt-4">
                    <a href="{{ route('penghuni.index') }}"
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

@endsection
