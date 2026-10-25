<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Opsional jika perlu soft delete

class Produk extends Model
{
    // Opsional: Jika menggunakan soft delete
    // use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'produks'; // Jika nama tabel berbeda

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kode',
        'nama',
        'harga',
        'stok',
        'gambar', // tambahkan ini
    ];
    

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'harga' => 'decimal:2',
        // tambahkan casting untuk tipe data khusus
    ];

    /**
     * Opsional: Jika menggunakan timestamps
     */
    // public $timestamps = true;

    /**
     * Opsional: Format tanggal kustom
     */
    // protected $dateFormat = 'U';

    /**
     * Opsional: Nilai default untuk atribut
     */
    protected $attributes = [
        'stok' => 0,
    ];

    /**
     * Relasi dengan model DetilTransaksi
     */
    public function detilTransaksis()
    {
        return $this->hasMany(DetilTransaksi::class);
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $term)
    {
        return $query->where('nama', 'like', "%{$term}%")
                    ->orWhere('kode', 'like', "%{$term}%");
    }
}