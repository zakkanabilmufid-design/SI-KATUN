<div style="background: linear-gradient(to bottom right, #e3f2fd, #ffffff); min-height: 100vh;">
    <div class="container py-4">
        <div class="row mb-3">
            <div class="col d-flex gap-2 flex-wrap justify-content-center">
                <button wire:click="pilihMenu('lihat')" class="btn {{ $pilihanMenu=='lihat' ? 'btn-primary' : 'btn-outline-primary'}} btn-lg shadow">
                    <i class="bi bi-grid"></i> Semua Produk
                </button>
                <button wire:click="pilihMenu('tambah')" class="btn {{ $pilihanMenu=='tambah' ? 'btn-success' : 'btn-outline-success'}} btn-lg shadow">
                    <i class="bi bi-plus-circle"></i> Tambah Produk
                </button>
                <button wire:loading class="btn btn-info btn-lg shadow" disabled>
                    <i class="bi bi-arrow-repeat"></i> Loading...
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                @if($pilihanMenu=='lihat')
                <div class="card shadow border-0 rounded-4">
                    <div class="card-header bg-primary text-white fw-semibold rounded-top-4">
                        Semua Produk
                    </div>
                    <div class="card-body">
                        <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4">
                        @foreach ($semuaProduk as $produk)
                            <div class="col">
                                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative produk-card transition-all">
                                    <div class="produk-img-wrapper">
                                        <img src="{{ $produk->gambar ? asset('storage/' . $produk->gambar) : 'https://via.placeholder.com/300x150?text=No+Image' }}"
                                            class="card-img-top object-fit-cover" style="height: 160px;">
                                    </div>
                                    <div class="card-body p-3">
                                        <h6 class="fw-bold text-truncate mb-1">{{ $produk->nama }}</h6>
                                        <small class="text-muted d-block">Kode: {{ $produk->kode }}</small>
                                        <small class="text-muted d-block">Harga: Rp {{ number_format($produk->harga, 0, ',', '.') }}</small>
                                        <small class="text-muted d-block">Stok: {{ $produk->stok }}</small>
                                    </div>
                                    <div class="card-footer border-top d-flex justify-content-between bg-white p-3">
                                        <button wire:click="pilihEdit({{ $produk->id }})" class="btn btn-sm btn-outline-primary rounded-pill">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </button>
                                        <button wire:click="pilihHapus({{ $produk->id }})" class="btn btn-sm btn-outline-danger rounded-pill">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </div>

                        <div class="mt-4 d-flex justify-content-center">
                            {{ $semuaProduk->links() }}
                        </div>
                    </div>
                </div>

                @elseif($pilihanMenu=='tambah' || $pilihanMenu=='edit')
                <div class="card shadow border-0 rounded-4">
                    <div class="card-header bg-success text-white fw-semibold rounded-top-4">
                        {{ $pilihanMenu == 'tambah' ? 'Tambah Produk' : 'Edit Produk' }}
                    </div>
                    <div class="card-body">
                        <form wire:submit.prevent="{{ $pilihanMenu == 'tambah' ? 'simpan' : 'simpanEdit' }}">
                            @foreach (['nama'=>'Nama', 'kode'=>'Kode / Barcode', 'harga'=>'Harga', 'stok'=>'Stok'] as $field => $label)
                                <div class="mb-3">
                                    <label class="fw-semibold">{{ $label }}</label>
                                    <input type="{{ $field == 'harga' || $field == 'stok' ? 'number' : 'text' }}" class="form-control rounded-3 shadow-sm" wire:model="{{ $field }}">
                                    @error($field)
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            @endforeach

                            <div class="mb-3">
                                <label class="fw-semibold">Gambar Produk {{ $pilihanMenu == 'edit' ? '(opsional)' : '' }}</label>
                                <input type="file" class="form-control rounded-3 shadow-sm" wire:model="gambar">
                                @error('gambar')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            @if ($gambar)
                                <div class="mb-3">
                                    <p class="mb-1">Preview:</p>
                                    <img src="{{ $gambar->temporaryUrl() }}" width="150" class="rounded shadow-sm">
                                </div>
                            @elseif ($produkTerpilih && $produkTerpilih->gambar)
                                <div class="mb-3">
                                    <p class="mb-1">Gambar Saat Ini:</p>
                                    <img src="{{ asset('storage/' . $produkTerpilih->gambar) }}" width="150" class="rounded shadow-sm">
                                </div>
                            @endif

                            <button type="submit" class="btn btn-success rounded-pill px-4 py-2">Simpan</button>
                        </form>
                    </div>
                </div>

                @elseif($pilihanMenu=='hapus')
                <div class="card border-danger shadow rounded-4">
                    <div class="card-header bg-danger text-white fw-semibold rounded-top-4">
                        Konfirmasi Hapus
                    </div>
                    <div class="card-body">
                        <p>Apakah Anda yakin ingin menghapus produk berikut?</p>
                        <ul>
                            <li><strong>Kode:</strong> {{ $produkTerpilih->kode }}</li>
                            <li><strong>Nama:</strong> {{ $produkTerpilih->nama }}</li>
                        </ul>
                        <div class="d-flex gap-2">
                            <button class="btn btn-danger px-4" wire:click="hapus">Hapus</button>
                            <button class="btn btn-secondary px-4" wire:click="batal">Batal</button>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>