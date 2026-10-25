<div>
    <div class="container mb-5 mt-4">
        <div class="row">
            <div class="col-12">
                <table class="table table-bordered">
                <thead class="table-primary">
    <tr>
        <th>#</th>
        <th>Waktu</th>
        <th>No Invoice</th>
        <th>User</th>
        <th>Total</th>
        <th>Metode</th> <!-- Kolom baru -->
        <th>Detail Produk</th>
    </tr>
</thead>
<tbody>
    @forelse($semuaTransaksi as $transaksi)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $transaksi->created_at->format('d M Y - H:i') }}</td>
        <td>{{ $transaksi->kode }}</td>
        <td>{{ $transaksi->user->name ?? '-' }}</td>
        <td class="text-success text-end">
            Rp {{ number_format($transaksi->total, 0, ',', '.') }}
        </td>
        <td>{{ ucfirst($transaksi->metode_pembayaran ?? '-') }}</td> <!-- Isi metode pembayaran -->
        <td>
            <button class="btn btn-sm btn-outline-primary" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#produkTransaksi{{ $transaksi->id }}"
                aria-expanded="false"
                aria-controls="produkTransaksi{{ $transaksi->id }}">
                Lihat Produk
            </button>
        </td>
    </tr>
    <tr class="collapse" id="produkTransaksi{{ $transaksi->id }}">
        <td colspan="7"> <!-- Perlu diubah jadi 7 kolom -->
            <ul class="list-group">
                @foreach($transaksi->detilTransaksi as $item)
                    @php $subtotal = $item->produk->harga * $item->jumlah; @endphp
                    @if($subtotal > 0)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>{{ $item->produk->nama }} (x{{ $item->jumlah }})</div>
                            <div>Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                        </li>
                    @endif
                @endforeach
            </ul>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="7" class="text-center text-muted">Belum ada transaksi.</td>
    </tr>
    @endforelse
</tbody>

                </table>
            </div>
        </div>
    </div>
</div>
