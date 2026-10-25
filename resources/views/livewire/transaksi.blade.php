<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">🛒 Transaksi Kasir</h4>
        <div>
            @if(!$transaksiAktif)
                <button class="btn btn-success" wire:click='transaksiBaru'>
                    <i class="bi bi-plus-circle"></i> Transaksi Baru
                </button>
            @else
                <button class="btn btn-danger" wire:click='batalTransaksi'>
                    <i class="bi bi-x-circle"></i> Batalkan Transaksi
                </button>
            @endif
            <button class="btn btn-info" wire:loading wire:target="transaksiBaru, batalTransaksi">
                <span class="spinner-border spinner-border-sm" role="status"></span> Loading...
            </button>
        </div>
    </div>

    @if($transaksiAktif)
    <div class="row g-3">
        <!-- Kiri: Produk & Daftar -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-3">🔍 Cari Produk</h5>

                    <div class="mb-3 position-relative">
                        <input type="text" class="form-control" placeholder="Cari produk..." 
                            wire:model.live.debounce.300ms="search">
                        
                        @if($search && count($produkList) > 0)
                            <div class="list-group position-absolute w-100 z-3 shadow-sm">
                                @foreach($produkList as $produk)
                                <button class="list-group-item list-group-item-action" 
                                        wire:click="pilihProduk({{ $produk['id'] }})">
                                    <strong>{{ $produk['nama'] }}</strong> - 
                                    Rp {{ number_format($produk['harga'], 0, ',', '.') }} 
                                    <span class="badge bg-secondary float-end">Stok: {{ $produk['stok'] }}</span>
                                </button>
                                @endforeach
                            </div>
                        @elseif(strlen($search) >= 2 && empty($produkList))
                            <small class="text-danger">Produk tidak ditemukan</small>
                        @endif
                    </div>

                    @if($produkTerpilih)
                        <div class="alert alert-primary">
                            <strong>{{ $produkTerpilih->nama }}</strong> - Masukkan jumlah:
                            <div class="input-group mt-2">
                                <input type="number" class="form-control" wire:model="jumlahProduk" min="1" max="{{ $produkTerpilih->stok }}">
                                <button class="btn btn-success" wire:click="tambahProdukKeTransaksi">Tambah</button>
                            </div>
                        </div>
                    @endif

                    <h6 class="mt-4">🧾 Daftar Produk</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($semuaProduk as $produk)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $produk->produk->kode }}</td>
                                    <td>{{ $produk->produk->nama }}</td>
                                    <td>Rp {{ number_format($produk->produk->harga, 0, ',', '.') }}</td>
                                    <td>{{ $produk->jumlah }}</td>
                                    <td>Rp {{ number_format($produk->produk->harga * $produk->jumlah, 0, ',', '.') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-danger" wire:click='hapusProduk({{ $produk->id }})'>
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @auth
                    <small class="text-muted">👤 {{ auth()->user()->name }}</small>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Kanan: Pembayaran -->
        <div class="col-md-4">
            <div class="card border-success shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title">💰 Total</h5>
                    <h3 class="text-success">Rp {{ number_format($totalSemuaBelanja, 0, ',', '.') }}</h3>
                </div>
            </div>

            <div class="mb-3">
                <label for="metodePembayaran" class="form-label">Metode Pembayaran</label>
                <select wire:model="metodePembayaran" class="form-select" id="metodePembayaran">
                    <option value="">-- Pilih Metode --</option>
                    <option value="tunai">Tunai</option>
                    <option value="qris">QRIS</option>
                </select>
            </div>


            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <label>Bayar</label>
                    <input type="number" class="form-control" placeholder="Masukkan nominal" wire:model.live="bayar">
                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <label>Kembalian</label>
                    <h5 class="text-info">Rp {{ number_format($kembalian, 0, ',', '.') }}</h5>
                </div>
            </div>

            @if($bayar && $metodePembayaran)
    @if($metodePembayaran === 'tunai')
        @if($kembalian < 0)
            <div class="alert alert-danger">
                <strong>Uang tidak cukup!</strong>
            </div>
        @else
            <button class="btn btn-success w-100" wire:click='transaksiSelesai'>
                <i class="bi bi-cash-coin"></i> Bayar Sekarang
            </button>
        @endif
    @else
        {{-- Untuk metode selain tunai (qris, transfer, ewallet, dll) --}}
        <button class="btn btn-success w-100" wire:click='transaksiSelesai'>
            <i class="bi bi-cash-coin"></i> Bayar Sekarang
        </button>
    @endif
@endif

        </div>
    </div>
    @endif
</div>
