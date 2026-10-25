<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Beranda;
use App\Livewire\Keuangan;
use App\Livewire\Laporan;
use App\Livewire\Produk;
use App\Livewire\Produkkasir;
use App\Livewire\Transaksi;
use App\Livewire\User;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes([
    'register' => false, // Menonaktifkan halaman register
]);


Route::get('/home', Beranda::class)->middleware(['auth'])->name('home');
Route::get('/user', User::class)->middleware(['auth'])->name('user');
Route::get('/laporan', Laporan::class)->middleware(['auth'])->name('laporan');
Route::get('/keuangan', Keuangan::class)->middleware(['auth'])->name('keuangan');
Route::get('/produk', Produk::class)->middleware(['auth'])->name('produk');
Route::get('/produk-kasir', Produkkasir::class)->middleware(['auth'])->name('produk.kasir');
Route::get('/transaksi', Transaksi::class)->middleware(['auth'])->name('transaksi');
Route::get('/cetak', [App\Http\Controllers\HomeController::class, 'cetak'])->middleware(['auth'])->name('cetak');