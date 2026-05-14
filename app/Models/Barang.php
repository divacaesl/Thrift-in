<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = [
        'kode_barang', 'penitip_id', 'kategori_id', 'nama_barang', 
        'deskripsi', 'kondisi', 'harga_jual', 'foto', 'status', 
        'tgl_masuk', 'tgl_terjual', 'catatan'
    ];

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
}
