<div>
    <div class="container">
        <div class="row my-3">
            <div class="col-12 d-flex gap-2">
                <button wire:click="pilihMenu('lihat')" 
                    class="btn {{ $pilihanMenu=='lihat' ? 'btn-primary' : 'btn-outline-primary'}} shadow-sm rounded-pill">
                    👥 Semua Pengguna
                </button>
                <button wire:click="pilihMenu('tambah')" 
                    class="btn {{ $pilihanMenu=='tambah' ? 'btn-success' : 'btn-outline-success'}} shadow-sm rounded-pill">
                    ➕ Tambah Pengguna
                </button>
                <button wire:loading class="btn btn-info shadow-sm rounded-pill">
                    🔄 Loading...
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                @if($pilihanMenu=='lihat')
                <div class="card shadow border-0">
                    <div class="card-header bg-primary text-white fw-bold">
                        Semua Pengguna
                    </div>
                    <div class="card-body">
                        <table class="table table-hover table-bordered align-middle text-center">
                            <thead class="table-primary">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Peran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($semuaPengguna as $pengguna)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $pengguna->name }}</td>
                                    <td>{{ $pengguna->email }}</td>
                                    <td><span class="badge bg-info text-dark">{{ ucfirst($pengguna->peran) }}</span></td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <button wire:click="pilihEdit({{ $pengguna->id }})" 
                                                class="btn btn-outline-warning btn-sm rounded-pill">
                                                ✏️ Edit
                                            </button>
                                            <button wire:click="pilihHapus({{ $pengguna->id }})" 
                                                class="btn btn-outline-danger btn-sm rounded-pill">
                                                🗑 Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                @elseif($pilihanMenu=='tambah' || $pilihanMenu=='edit')
                <div class="card shadow border-0">
                    <div class="card-header bg-success text-white fw-bold">
                        {{ $pilihanMenu == 'tambah' ? 'Tambah Pengguna' : 'Edit Pengguna' }}
                    </div>
                    <div class="card-body">
                        <form wire:submit.prevent='{{ $pilihanMenu == "tambah" ? "simpan" : "simpanEdit" }}'>
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" class="form-control" wire:model='nama'>
                                @error('nama') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" wire:model='email'>
                                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" id="password" class="form-control" wire:model='password'>
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="password.type = password.type === 'password' ? 'text' : 'password'">
                                        👁
                                    </button>
                                </div>
                                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Peran</label>
                                <select class="form-select" wire:model='peran'>
                                    <option value="">--Pilih Peran--</option>
                                    <option value="kasir">Kasir</option>
                                    <option value="admin">Admin</option>
                                </select>
                                @error('peran') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary shadow-sm">💾 Simpan</button>
                                @if($pilihanMenu == 'edit')
                                <button type="button" wire:click='batal' class="btn btn-secondary shadow-sm">✖ Batal</button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                @elseif($pilihanMenu=='hapus')
                <div class="card shadow border-danger">
                    <div class="card-header bg-danger text-white fw-bold">
                        Konfirmasi Hapus
                    </div>
                    <div class="card-body">
                        <p>Apakah kamu yakin ingin menghapus pengguna ini?</p>
                        <p><strong>Nama:</strong> {{ $penggunaTerpilih->name }}</p>
                        <div class="d-flex gap-2">
                            <button class="btn btn-danger shadow-sm" wire:click='hapus'>🗑 Hapus</button>
                            <button class="btn btn-secondary shadow-sm" wire:click='batal'>✖ Batal</button>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
