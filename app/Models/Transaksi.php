<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [
        'kode_transaksi', 'barang_id', 'nama_pembeli', 'no_hp_pembeli', 
        'harga_jual', 'komisi_persen', 'komisi_nominal', 'hasil_penitip', 
        'metode_bayar', 'tgl_transaksi', 'kasir_id'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }
}
