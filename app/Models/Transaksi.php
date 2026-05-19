<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $guarded = [];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function alamatPengiriman()
    {
        return $this->belongsTo(AlamatPengiriman::class, 'alamat_pengiriman_id');
    }

    public function complaint()
    {
        return $this->hasOne(Complaint::class);
    }
}
