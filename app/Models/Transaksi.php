<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'total',
        'user_id',
        'status',
        'metode_pembayaran' // Tambahkan ini
    ];
    

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detilTransaksi()
    {
        return $this->hasMany(DetilTransaksi::class);
    }
}