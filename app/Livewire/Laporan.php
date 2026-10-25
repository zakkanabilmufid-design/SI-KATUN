<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

class Laporan extends Component
{
    public $semuaTransaksi;

    public function mount()
    {
        $this->semuaTransaksi = Transaksi::where('user_id', auth()->id())
        ->where('total', '>', 0) // hanya transaksi dengan total lebih dari 0
        ->latest()
        ->get();
    
    }
    

    public function render()
    {
        return view('livewire.laporan', [
            'semuaTransaksi' => Transaksi::where('user_id', auth()->id())
                ->where('total', '>', 0)
                ->latest()
                ->get()
        ]);
    }
    
    
}
