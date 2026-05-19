<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $guarded = [];

    public function penitip()
    {
        return $this->belongsTo(Penitip::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function transaksi()
    {
        return $this->hasOne(Transaksi::class);
    }

    public function ulasans()
    {
        return $this->hasMany(Ulasan::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function negos()
    {
        return $this->hasMany(NegoHarga::class);
    }
}
