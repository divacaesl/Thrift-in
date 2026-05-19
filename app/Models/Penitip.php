<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penitip extends Model
{
    protected $fillable = [
        'kode_penitip', 'nama', 'no_hp', 'email', 'alamat', 
        'nama_bank', 'no_rekening', 'status',
        'user_id', 'logo_toko', 'banner_toko', 'deskripsi_toko',
        'ktp', 'selfie', 'is_verified', 'saldo', 'auto_reply_message', 'is_auto_reply_enabled'
    ];

    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }

    public function pencairans()
    {
        return $this->hasMany(Pencairan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function followers()
    {
        return $this->hasMany(Follow::class);
    }
}
