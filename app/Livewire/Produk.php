<?php

namespace App\Livewire;
use App\Models\Produk as ModelProduk;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;

class Produk extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $gambar;
    public $pilihanMenu = 'lihat';
    public $kode;
    public $nama;
    public $harga;
    public $stok;
    public $produkTerpilih;

    public function mount()
    {
        if(Auth::user()->peran != 'admin') {
            abort(403);
        }
    }

    public function pilihEdit($id)
    {
        $this->produkTerpilih = ModelProduk::findOrFail($id);
        $this->nama = $this->produkTerpilih->nama;
        $this->kode = $this->produkTerpilih->kode;
        $this->harga = $this->produkTerpilih->harga;
        $this->stok = $this->produkTerpilih->stok;
        $this->pilihanMenu = 'edit';
    }

    public function simpanEdit()
    {
        $this->validate([
            'nama' => 'required',
            'kode' => 'required|unique:produks,kode,' . $this->produkTerpilih->id,
            'harga' => 'required',
            'stok' => 'required',
            'gambar' => 'nullable|image|max:1024', // validasi gambar
        ],[
            'nama.required' => 'Nama harus diisi',
            'kode.required' => 'Kode harus diisi',
            'kode.unique' => 'Kode telah digunakan',
            'harga.required' => 'Harga harus diisi',
            'stok.required' => 'Stok harus diisi',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.max' => 'Ukuran gambar maksimal 1MB',
        ]);
    
        $simpan = $this->produkTerpilih;
        $simpan->nama = $this->nama;
        $simpan->kode = $this->kode;
        $simpan->stok = $this->stok;
        $simpan->harga = $this->harga;
    
        // Jika user mengupload gambar baru
        if ($this->gambar) {
            // Hapus gambar lama jika ada
            if ($simpan->gambar && Storage::disk('public')->exists($simpan->gambar)) {
                Storage::disk('public')->delete($simpan->gambar);
            }
    
            // Simpan gambar baru
            $path = $this->gambar->store('produk', 'public');
            $simpan->gambar = $path;
        }
    
        $simpan->save();
    
        $this->reset();
        $this->pilihanMenu = 'lihat';
    }

    public function pilihHapus($id)
    {
        $this->produkTerpilih = ModelProduk::findOrFail($id);
        $this->pilihanMenu = 'hapus';
    }

    public function hapus()
    {
        $this->produkTerpilih->delete();
        $this->reset();
    }

    public function batal()
    {
        $this->reset();
    }

    public function simpan()
    {
        $this->validate([
            'nama' => 'required',
            'kode' => 'required|unique:produks,kode',
            'harga' => 'required',
            'stok' => 'required',
            'gambar' => 'nullable|image|max:1024', // Validasi gambar
        ],[
            'nama.required' => 'Nama harus diisi',
            'kode.required' => 'kode harus diisi',
            'kode.unique' => 'kode telah digunakan',
            'harga.required' => 'harga harus diisi',
            'stok.required' => 'stok harus diisi',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.max' => 'Ukuran gambar maksimal 1MB',
        ]);
    
        $simpan = new ModelProduk();
        $simpan->nama = $this->nama;
        $simpan->kode = $this->kode;
        $simpan->stok = $this->stok;
        $simpan->harga = $this->harga;
    
        // Simpan gambar jika ada
        if ($this->gambar) {
            $path = $this->gambar->store('produk', 'public');
            $simpan->gambar = $path;
        }
    
        $simpan->save();
    
        $this->reset(['nama','kode','harga','stok','gambar']);
        $this->pilihanMenu = 'lihat';
    }

    public function updatedPilihanMenu()
    {
        $this->resetPage(); // Reset ke halaman 1 saat menu berubah
    }

    public function pilihMenu($menu)
    {
        $this->pilihanMenu = $menu;
    }
    
    public function render()
    {
        return view('livewire.produk')->with([
            'semuaProduk' => ModelProduk::latest()->paginate(24)
        ]);
    }
    
}
