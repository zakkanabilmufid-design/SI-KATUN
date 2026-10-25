<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Keuangan extends Component
{
    use WithPagination;

    public $filterBulanTransaksi;
    public $kasirTerpilih;
    public $cari;
    protected $listeners = ['updateGrafik', 'getGrafikData'];
    public $transaksiTerpilihId = null;
    public $detailTransaksi = [];
    

    protected $queryString = [
        'filterBulanTransaksi' => ['except' => ''],
        'kasirTerpilih' => ['except' => ''],
        'cari' => ['except' => ''],
    ];

    public function getGrafikData()
    {
        $grafikData = $this->siapkanDataGrafik();
    
        // ✅ Ini yang benar di Livewire v3
        $this->dispatch('updateGrafik', labels: $grafikData['labels'], data: $grafikData['data']);

    }
    

    public function mount()
    {
        abort_unless(Auth::user()->peran === 'admin', 403);
        $this->filterBulanTransaksi = now()->format('Y-m');
        $this->getGrafikData(); // ✅ Tambahkan ini
    }
    

    // Properti computed untuk opsi bulan
    public function getBulanOptionsProperty()
    {
        return collect(range(0, 11))->map(function ($i) {
            $month = now()->subMonths($i);
            return [
                'value' => $month->format('Y-m'),
                'label' => $month->translatedFormat('M Y')
            ];
        });
    }

    public function lihatDetail($transaksiId)
    {
        if ($this->transaksiTerpilihId === $transaksiId) {
            // Jika klik transaksi yang sama, tutup detail
            $this->transaksiTerpilihId = null;
            $this->detailTransaksi = [];
        } else {
            // Jika klik transaksi berbeda, tampilkan detail baru
            $this->transaksiTerpilihId = $transaksiId;
            $this->loadDetailTransaksi($transaksiId);
        }
    }

    protected function loadDetailTransaksi($transaksiId)
    {
        // Gunakan full namespace untuk memastikan
        $transaksi = \App\Models\Transaksi::with(['detilTransaksi.produk'])->find($transaksiId);
        
        if ($transaksi) {
            $this->detailTransaksi = $transaksi->detilTransaksi->map(function ($item) {
                return [
                    'produk_nama' => $item->produk->nama,
                    'produk_kode' => $item->produk->kode,
                    'jumlah' => $item->jumlah,
                    'harga' => $item->produk->harga,
                    'subtotal' => $item->jumlah * $item->produk->harga
                ];
            })->toArray();
        } else {
            $this->detailTransaksi = [];
        }
    }
    public function updatedKasirTerpilih()
    {
        $this->resetPage();
    }
    
    public function updatedCari()
    {
        $this->resetPage();
    }
    public function getTotalPendapatanProperty()
    {
        $bulan = Carbon::createFromFormat('Y-m', $this->filterBulanTransaksi);
        return Transaksi::where('status', 'selesai')
            ->whereYear('created_at', $bulan->year)
            ->whereMonth('created_at', $bulan->month)
            ->sum('total');
    }
        

    
    public function render()
    {
        $semuaTransaksi = Transaksi::with(['user'])
            ->where('status', 'selesai')
            ->when($this->filterBulanTransaksi, function($query) {
                $bulan = Carbon::createFromFormat('Y-m', $this->filterBulanTransaksi);
                $query->whereYear('created_at', $bulan->year)
                      ->whereMonth('created_at', $bulan->month);
            })
            ->when($this->kasirTerpilih, function($query) {
                $query->where('user_id', $this->kasirTerpilih);
            })
            ->when($this->cari, function($query) {
                $query->where('kode', 'like', '%'.$this->cari.'%');
            })
            ->latest()
            ->paginate(10);
    
        // Di method render()
        return view('livewire.keuangan', [
            'semuaTransaksi' => $semuaTransaksi,
            'daftarKasir' => User::where('peran', 'kasir')->get(),
            'grafikData' => $this->siapkanDataGrafik(), // Ubah dari $dataGrafik ke $grafikData
            'bulanOptions' => $this->bulanOptions
        ]);
    }


    public function updatedFilterBulanTransaksi()
    {
        $this->resetPage();
        $this->getGrafikData(); // ✅ ini yang betul
    }
    
    
    protected function siapkanDataGrafik()
    {
        if (!$this->filterBulanTransaksi) {
            return ['labels' => [], 'data' => []];
        }
    
        try {
            $bulan = Carbon::createFromFormat('Y-m', $this->filterBulanTransaksi);
            
            $transaksi = Transaksi::where('status', 'selesai')
                ->whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->selectRaw('DAY(created_at) as hari, SUM(total) as total')
                ->groupBy('hari')
                ->orderBy('hari')
                ->get();
    
            $labels = [];
            $data = [];
    
            // Buat data untuk semua hari dalam bulan tersebut
            $daysInMonth = $bulan->daysInMonth;
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $labels[] = $day;
                $found = $transaksi->firstWhere('hari', $day);
                $data[] = $found ? (int) $found->total : 0;
            }
            
    
            return [
                'labels' => $labels,
                'data' => $data
            ];
        } catch (\Exception $e) {
            return ['labels' => [], 'data' => []];
        }
    }
    
}