<?php

namespace App\Livewire;

use App\Models\DetilTransaksi;
use Livewire\Component;
use App\Models\Transaksi as ModelsTransaksi;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;

class Transaksi extends Component
{
    public $metodePembayaran = '';
    public $kode, $total, $kembalian, $totalSemuaBelanja;
    public $bayar = 0;
    public $transaksiAktif;
    public $search = '';
    public $jumlahProduk = 1;
    public $produkTerpilih;
    public $produkList = [];

    public function updatedSearch($value)
    {
        $this->produkList = Produk::when($value, function($query) use ($value) {
                                $query->where('nama', 'like', '%'.$value.'%')
                                      ->orWhere('kode', 'like', '%'.$value.'%');
                            })
                            ->where('stok', '>', 0) // Hanya tampilkan yang stok tersedia
                            ->limit(10) // Batasi hasil
                            ->get()
                            ->toArray();
    }
    
    
    public function pilihProduk($id)
    {
        $this->produkTerpilih = Produk::find($id);
        $this->search = ''; // kosongkan search setelah memilih
        $this->produkList = [];
    }

    public function tambahProdukKeTransaksi()
    {
        if (!$this->transaksiAktif || !$this->produkTerpilih) {
            return;
        }

        if ($this->produkTerpilih->stok < $this->jumlahProduk) {
            session()->flash('error', 'Stok tidak mencukupi.');
            return;
        }

        $detil = DetilTransaksi::firstOrNew([
            'transaksi_id' => $this->transaksiAktif->id,
            'produk_id' => $this->produkTerpilih->id
        ], [
            'jumlah' => 0,
        ]);

        $detil->jumlah += $this->jumlahProduk;
        $detil->save();

        $this->produkTerpilih->stok -= $this->jumlahProduk;
        $this->produkTerpilih->save();

        $this->produkTerpilih = null;
        $this->jumlahProduk = 1;
        $this->search = '';
        $this->produkList = [];
    }

    public function mount()
    {
        if (Auth::user()->peran != 'kasir') {
            abort(403);
        }
    }

    public function transaksiBaru()
    {
        $this->reset();
        $this->transaksiAktif = new ModelsTransaksi();
        $this->transaksiAktif->kode = 'INV/' . date('YmdHis');
        $this->transaksiAktif->total = 0;
        $this->transaksiAktif->status = 'pending';
        $this->transaksiAktif->user_id = Auth::id();
        $this->transaksiAktif->save();
    }

    public function hapusProduk($id)
    {
        $detil = DetilTransaksi::find($id);
        if ($detil) {
            $produk = Produk::find($detil->produk_id);
            if ($produk) {
                $produk->stok += $detil->jumlah;
                $produk->save();
            }
        }
        $detil->delete();
    }

    public function transaksiSelesai()
    {
        if (!$this->metodePembayaran) {
            session()->flash('error', 'Silakan pilih metode pembayaran.');
            return;
        }
    
        $this->transaksiAktif->total = $this->totalSemuaBelanja;
        $this->transaksiAktif->status = 'selesai';
        $this->transaksiAktif->metode_pembayaran = $this->metodePembayaran; // << Tambahkan ini
        $this->transaksiAktif->save();
    
        $this->reset();
    }
    
    

    public function batalTransaksi()
    {
        if ($this->transaksiAktif) {
            $detilTransaksi = DetilTransaksi::where('transaksi_id', $this->transaksiAktif->id)->get();
            foreach ($detilTransaksi as $detil) {
                $produk = Produk::find($detil->produk_id);
                if ($produk) {
                    $produk->stok += $detil->jumlah;
                    $produk->save();
                }
                $detil->delete();
            }
            $this->transaksiAktif->delete();
        }
        $this->reset();
    }

    public function updatedKode()
    {
        $produk = Produk::where('kode', $this->kode)->first();
        if ($produk && $produk->stok > 0) {
            $detil = DetilTransaksi::firstOrNew([
                'transaksi_id' => $this->transaksiAktif->id,
                'produk_id' => $produk->id
            ], [
                'jumlah' => 0,
            ]);
            $detil->jumlah += 1;
            $detil->save();
            $produk->stok -= 1;
            $produk->save();
            $this->reset('kode');
        }
    }

    public function updatedBayar()
    {
        if ($this->bayar >= 0) {
            $this->kembalian = $this->bayar - $this->totalSemuaBelanja;
        }
    }

    
    public function render()
    {
        logger('Produk List: ', $this->produkList);
        logger('Search Term: '.$this->search);
        if ($this->transaksiAktif) {
            $semuaProduk = DetilTransaksi::with('produk')
                              ->where('transaksi_id', $this->transaksiAktif->id)
                              ->get();
            $this->totalSemuaBelanja = $semuaProduk->sum(fn($detil) => $detil->produk->harga * $detil->jumlah);
        } else {
            $semuaProduk = collect();
        }
    
        return view('livewire.transaksi', [
            'semuaProduk' => $semuaProduk,
            'produkList' => collect($this->produkList) // Ensure this is always a collection
        ]);
    }
    
}
