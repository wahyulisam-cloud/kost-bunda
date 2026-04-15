@forelse($pemasukan as $p)
<tr class="text-center align-middle">

    {{-- Nama --}}
    <td class="fw-semibold" style="color:#1e3a5f;">
        {{ $p->penghuni->nama_penghuni ?? '-' }}
    </td>

    {{-- Kamar --}}
    <td>
        {{ $p->penghuni->kamar->nomor_kamar ?? '-' }}
    </td>

    {{-- Bulan --}}
    <td>
        {{ $p->nama_bulan }}
    </td>

    {{-- Tahun --}}
    <td>
        {{ $p->tahun }}
    </td>

   {{-- Nominal --}}
<td class="fw-semibold" style="color:#1e3a5f;">
    Rp {{ number_format($p->nominal, 0, ',', '.') }}
</td>

    <td>
    <span class="badge rounded-pill px-3 py-2"
        style="
            font-weight:500;
            background-color: {{ $p->status == 'lunas' ? '#1e3a5f' : '#e2e8f0' }};
            color: {{ $p->status == 'lunas' ? '#ffffff' : '#1e3a5f' }};
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        ">
        {{ ucfirst($p->status) }}
    </span>
</td>

    {{-- Aksi --}}
    <td>

        <form action="{{ route('pemasukan.destroy', $p->id_pemasukan) }}"
              method="POST"
              class="d-inline"
              onsubmit="return confirm('Yakin ingin menghapus data ini?')">

            @csrf
            @method('DELETE')

            <button type="submit"
                    class="btn btn-sm btn-premium btn-danger-soft">
                Hapus
            </button>

        </form>

    </td>

</tr>

@empty
<tr>
    <td colspan="7" class="text-center py-4" style="color:#64748B;">
        <i class="bi bi-inbox me-2"></i>
        Data tidak ditemukan
    </td>
</tr>
@endforelse
