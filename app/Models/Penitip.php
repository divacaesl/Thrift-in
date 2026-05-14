<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penitip extends Model
{
    protected $fillable = [
        'kode_penitip', 'nama', 'no_hp', 'email', 'alamat', 
        'nama_bank', 'no_rekening', 'status'
    ];

    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }

    public function pencairans()
    {
        return $this->hasMany(Pencairan::class);
    }
}
