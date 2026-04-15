@forelse($pengeluaran as $item)
<tr class="text-center align-middle">

    {{-- Jenis Pengeluaran --}}
    <td class="fw-semibold" style="color:#1e3a5f;">
        {{ optional($item->kategori)->nama_kategori ?? '-' }}
    </td>

    {{-- Keterangan --}}
    <td>
 @if(!empty($item->keterangan))
        {{ $item->keterangan }}
    @else
        {{ optional($item->kategori)->nama_kategori }}
    @endif
    </td>

    {{-- Tanggal Dicatat --}}
    <td>
        {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
    </td>

    {{-- Tanggal Bayar --}}
    <td>
        @if($item->tanggal_bayar)
            <span class="badge rounded-pill px-3 py-2"
                  style="
                    background-color:#e2e8f0;
                    color:#1e3a5f;
                    font-weight:500;
                    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
                  ">
                {{ \Carbon\Carbon::parse($item->tanggal_bayar)->translatedFormat('d M Y') }}
            </span>
        @else
            <span class="badge rounded-pill px-3 py-2"
                  style="
                    background-color:#f1f5f9;
                    color:#64748b;
                    font-weight:500;
                  ">
                Belum Dibayar
            </span>
        @endif
    </td>

    {{-- Nominal --}}
    <td class="fw-semibold" style="color:#1e3a5f;">
        Rp {{ number_format($item->nominal, 0, ',', '.') }}
    </td>

    {{-- Aksi --}}
    <td>
        <form action="{{ route('pengeluaran.destroy', $item->id_pengeluaran) }}"
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
    <td colspan="6" class="text-center py-4" style="color:#64748B;">
        <i class="bi bi-inbox me-2"></i>
        Data tidak ditemukan
    </td>
</tr>
@endforelse