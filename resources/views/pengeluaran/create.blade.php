@extends('layouts.app')

@section('page-title', 'Tambah Pengeluaran')

@section('content')

<div class="container mt-4">

    <div class="card border-0 shadow-sm"
         style="border-radius:20px; background:#ffffff;">

        {{-- HEADER --}}
        <div class="card-header text-white"
             style="background:#1e3a5f; border-radius:20px 20px 0 0;">
            <h5 class="mb-0">Tambah Data Pengeluaran</h5>
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

            <form action="{{ route('pengeluaran.store') }}" method="POST">
                @csrf

                <div class="row g-4">

                    {{-- Tanggal Dicatat --}}
                    <div class="col-md-6">
                        <label class="fw-semibold mb-1">Tanggal Dicatat</label>
                       <input type="date"
       name="tanggal"
       class="form-control"
       style="border-radius:12px;"
       value="{{ date('Y-m-d') }}"
       readonly>
                    </div>

                    {{-- Tanggal Bayar --}}
                    <div class="col-md-6">
                        <label class="fw-semibold mb-1">Tanggal Bayar</label>
                        <input type="date"
                               name="tanggal_bayar"
                               class="form-control"
                               style="border-radius:12px;"
                               value="{{ old('tanggal_bayar') }}">
                    </div>

                    {{-- Kategori --}}
                    <div class="col-md-6">
                        <label class="fw-semibold mb-1">Jenis Pengeluaran</label>
                        <select name="id_kategori"
                                id="kategori"
                                class="form-control"
                                style="border-radius:12px;"
                                required>

                            <option value="">-- Pilih --</option>

                            @foreach($kategori as $k)
                                @if(strtolower($k->nama_kategori) !== 'lainnya')
                                    <option value="{{ $k->id_kategori }}"
                                            data-nominal="{{ $k->nominal_default }}"
                                            {{ old('id_kategori') == $k->id_kategori ? 'selected' : '' }}>
                                        {{ $k->nama_kategori }}
                                    </option>
                                @endif
                            @endforeach

                            <option disabled>────────────</option>

                            @foreach($kategori as $k)
                                @if(strtolower($k->nama_kategori) === 'lainnya')
                                    <option value="{{ $k->id_kategori }}"
                                            data-nominal="{{ $k->nominal_default }}"
                                            {{ old('id_kategori') == $k->id_kategori ? 'selected' : '' }}>
                                        {{ $k->nama_kategori }}
                                    </option>
                                @endif
                            @endforeach

                        </select>
                    </div>

                    {{-- Keterangan --}}
                    <div class="col-md-6">
                        <label class="fw-semibold mb-1">Keterangan</label>
                        <input type="text"
                               id="keterangan"
                               name="keterangan"
                               value="{{ old('keterangan') }}"
                               class="form-control"
                               style="border-radius:12px;"
                               placeholder="Isi keterangan pengeluaran">
                    </div>

                    {{-- Nominal --}}
                    <div class="col-md-6">
                        <label class="fw-semibold mb-1">Jumlah (Rp)</label>
                        <input type="number"
                               id="nominal"
                               name="nominal"
                               value="{{ old('nominal') }}"
                               class="form-control"
                               style="border-radius:12px;"
                               placeholder="Contoh: 350000"
                               required>
                    </div>

                </div>

                {{-- BUTTON --}}
                <div class="text-end mt-4">

                    <a href="{{ route('pengeluaran.index') }}"
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

{{-- SCRIPT LOGIC --}}
<script>
document.addEventListener("DOMContentLoaded", function(){

    const kategori = document.getElementById('kategori');
    const ket = document.getElementById('keterangan');
    const nominal = document.getElementById('nominal');

    function handleKategori(){

        const selectedOption = kategori.options[kategori.selectedIndex];
        if(!selectedOption) return;

        const selectedText = selectedOption.text.toLowerCase().trim();
        const defaultNominal = selectedOption.getAttribute('data-nominal');

        if(selectedText === 'lainnya'){
            // jika pilih lainnya → keterangan otomatis
            ket.value = "Pengeluaran Lainnya";
            ket.readOnly = true;
            ket.required = false;
        }else{
            // selain lainnya → user boleh isi keterangan
            ket.readOnly = false;
            ket.required = true;
            ket.value = "";
        }

        // isi nominal default jika ada
        if(defaultNominal && !nominal.value){
            nominal.value = defaultNominal;
        }
    }

    kategori.addEventListener('change', handleKategori);

    handleKategori();
});
</script>

@endsection