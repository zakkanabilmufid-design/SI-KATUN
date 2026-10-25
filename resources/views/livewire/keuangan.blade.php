<div>
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <div class="card border-primary">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Laporan Transaksi</h4>

                        {{-- Grafik Laporan Modern --}}
                    <div class="mb-4">
                        <div class="p-3 border rounded shadow-sm bg-white">
                            <h5 class="mb-3"><i class="fas fa-chart-bar me-2 text-primary"></i>Grafik Pendapatan Bulanan</h5>
                            <div wire:ignore>
                                <canvas id="grafikKeuangan" style="height: 300px;"></canvas>
                            </div>
                            <p class="text-end mt-2 fw-bold">
                                Total Pendapatan: Rp {{ number_format($this->totalPendapatan, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    </div>
                    <div class="card-body">
                        {{-- Filter Tabel --}}
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label>Filter Transaksi (Bulan)</label>
                                    <select wire:model.live="filterBulanTransaksi" class="form-control">
                                        @foreach($bulanOptions as $option)
                                            <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            <div class="col-md-3">
                                <label>Kasir</label>
                                <select wire:model.live="kasirTerpilih" class="form-control">
                                    <option value="">Semua Kasir</option>
                                    @foreach($daftarKasir as $kasir)
                                        <option value="{{ $kasir->id }}">{{ $kasir->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Cari No INV</label>
                                <input type="text" wire:model.live="cari" class="form-control" placeholder="Cari No INV">
                            </div>
                        </div>


                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>No INV</th>
                                    <th>Total</th>
                                    <th>Kasir</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($semuaTransaksi as $transaksi)
                                    <tr>
                                        <td>{{ ($semuaTransaksi->currentPage() - 1) * $semuaTransaksi->perPage() + $loop->iteration }}</td>
                                        <td>{{ $transaksi->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $transaksi->kode }}</td>
                                        <td>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
                                        <td>{{ $transaksi->user->name ?? '-' }}</td>
                                        <td>
                                            <button wire:click="lihatDetail('{{ $transaksi->id }}')" 
                                                    class="btn btn-sm {{ $transaksiTerpilihId == $transaksi->id ? 'btn-danger' : 'btn-info' }}">
                                                <i class="fas {{ $transaksiTerpilihId == $transaksi->id ? 'fa-times' : 'fa-eye' }}"></i>
                                                {{ $transaksiTerpilihId == $transaksi->id ? 'Tutup' : 'Detail' }}
                                            </button>
                                        </td>
                                    </tr>
                                    @if($transaksiTerpilihId == $transaksi->id)
                                        <tr>
                                            <td colspan="6">
                                                <div class="p-3 bg-light">
                                                    <h5 class="mb-3">Detail Transaksi #{{ $transaksi->kode }}</h5>
                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-bordered">
                                                            <thead class="bg-primary text-white">
                                                                <tr>
                                                                    <th>No</th>
                                                                    <th>Kode Produk</th>
                                                                    <th>Nama Produk</th>
                                                                    <th>Jumlah</th>
                                                                    <th>Harga Satuan</th>
                                                                    <th>Subtotal</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($detailTransaksi as $index => $detail)
                                                                    <tr>
                                                                        <td>{{ $index + 1 }}</td>
                                                                        <td>{{ $detail['produk_kode'] }}</td>
                                                                        <td>{{ $detail['produk_nama'] }}</td>
                                                                        <td>{{ $detail['jumlah'] }}</td>
                                                                        <td>Rp {{ number_format($detail['harga'], 0, ',', '.') }}</td>
                                                                        <td>Rp {{ number_format($detail['subtotal'], 0, ',', '.') }}</td>
                                                                    </tr>
                                                                @endforeach
                                                                <tr class="table-primary fw-bold">
                                                                    <td colspan="5" class="text-end">Total Transaksi</td>
                                                                    <td>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada transaksi ditemukan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>


                        <div class="mt-3">
                            {{ $semuaTransaksi->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let grafikChart;

    Livewire.on('updateGrafik', ({ labels, data }) => {
        const ctx = document.getElementById('grafikKeuangan').getContext('2d');

        // Hapus grafik lama jika ada
        if (grafikChart) {
            grafikChart.destroy();
        }

        // Buat grafik baru
        grafikChart = new Chart(ctx, {
            type: 'bar', // Bisa juga 'line'
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan Harian (Rp)',
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    barThickness: 20
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    });

    // Trigger grafik awal saat halaman load
    document.addEventListener("livewire:load", function () {
        Livewire.dispatch('getGrafikData');
    });
</script>
@endpush

</div>