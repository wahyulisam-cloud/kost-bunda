@extends('layouts.app')

@section('page-title', 'Data Penghuni')

@section('content')

<div class="container mt-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold" style="color:#1e3a5f;">
            Data Penghuni
        </h4>

       <a href="{{ route('penghuni.create') }}"
   class="btn btn-premium btn-navy-gradient">
    + Tambah Penghuni
</a>

    </div>

    {{-- Card Table --}}
    <div class="card border-0 shadow-sm"
         style="border-radius:20px; background:#ffffff;">

        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead style="background:#f4f1e6;">
                        <tr class="text-center">
                            <th class="fw-bold" style="color:#1e3a5f;">No</th>
                            <th class="fw-bold" style="color:#1e3a5f;">Nama</th>
                            <th class="fw-bold" style="color:#1e3a5f;">NIK</th>
                            <th class="fw-bold" style="color:#1e3a5f;">No HP</th>
                            <th class="fw-bold" style="color:#1e3a5f;">Kamar</th>
                            <th class="fw-bold" style="color:#1e3a5f;">Tanggal Masuk</th>
                            <th class="fw-bold" style="color:#1e3a5f;">Status</th>
                            <th class="fw-bold" style="color:#1e3a5f;" width="170">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($penghuni as $item)
                        <tr class="text-center">
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-start fw-semibold">
                                {{ $item->nama_penghuni }}
                            </td>
                            <td>{{ $item->NIK }}</td>
                            <td>{{ $item->no_hp }}</td>
                            <td>{{ $item->nomor_kamar ?? '-' }}</td>
                            <td>{{ $item->tanggal_masuk }}</td>
                            <td>
                                @if($item->status == 'aktif')
                                    <span class="badge px-3 py-2"
                                          style="background:#e6edf5; color:#1e3a5f; font-weight:500;">
                                        Aktif
                                    </span>
                                @else
                                    <span class="badge px-3 py-2"
                                          style="background:#f2f2f2; color:#6c757d;">
                                        Keluar
                                    </span>
                                @endif
                            </td>
                            <td>

                                <button class="btn btn-sm btn-premium btn-navy"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $item->id_penghuni }}">
                                    Edit
                                </button>

                                <form action="{{ route('penghuni.destroy',$item->id_penghuni) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-premium btn-danger-soft"
                                            onclick="return confirm('Yakin ingin hapus?')">
                                        Hapus
                                    </button>
                                </form>

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                Belum ada data penghuni.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>


{{-- ================= MODAL EDIT ================= --}}
{{-- ================= MODAL EDIT ================= --}}
@foreach($penghuni as $item)
<div class="modal fade" id="editModal{{ $item->id_penghuni }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:18px;">

            <form action="{{ route('penghuni.update',$item->id_penghuni) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header text-white"
                     style="background:#1e3a5f; border-radius:18px 18px 0 0;">
                    <h5 class="modal-title">Edit Penghuni</h5>
                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">

                        {{-- Nama --}}
                        <div class="col-md-6">
                            <label class="fw-semibold">Nama</label>
                            <input type="text"
                                   name="nama_penghuni"
                                   class="form-control"
                                   style="border-radius:10px;"
                                   value="{{ $item->nama_penghuni }}"
                                   required>
                        </div>

                        {{-- NIK --}}
                        <div class="col-md-6">
                            <label class="fw-semibold">NIK</label>
                            <input type="text"
                                   name="NIK"
                                   class="form-control"
                                   style="border-radius:10px;"
                                   value="{{ $item->NIK }}"
                                   required>
                        </div>

                        {{-- No HP --}}
                        <div class="col-md-6">
                            <label class="fw-semibold">No HP</label>
                            <input type="text"
                                   name="no_hp"
                                   class="form-control"
                                   style="border-radius:10px;"
                                   value="{{ $item->no_hp }}"
                                   required>
                        </div>

                        {{-- Kamar --}}
                        <div class="col-md-6">
                            <label class="fw-semibold">Kamar</label>

                            <!-- 🔥 FIX PENTING: kirim id_kamar -->
                            <input type="hidden"
                                   name="id_kamar"
                                   value="{{ $item->id_kamar }}">

                            <input type="text"
                                   class="form-control"
                                   style="border-radius:10px;"
                                   value="{{ $item->nomor_kamar }}"
                                   readonly>
                        </div>

                        {{-- Tanggal Masuk --}}
                        <div class="col-md-6">
                            <label class="fw-semibold">Tanggal Masuk</label>
                            <input type="date"
                                   name="tanggal_masuk"
                                   class="form-control"
                                   style="border-radius:10px;"
                                   value="{{ $item->tanggal_masuk }}"
                                   required>
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6">
                            <label class="fw-semibold mb-2" style="color:#1e3a5f;">
                                Status
                            </label>

                            <select name="status"
                                    class="form-select custom-select-status"
                                    required>
                                <option value="aktif"
                                    {{ $item->status=='aktif'?'selected':'' }}>
                                    Aktif
                                </option>

                                <option value="keluar"
                                    {{ $item->status=='keluar'?'selected':'' }}>
                                    Keluar
                                </option>
                            </select>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit"
                            class="btn btn-premium btn-navy-gradient">
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endforeach
@endsection
