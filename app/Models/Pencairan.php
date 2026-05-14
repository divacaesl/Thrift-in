<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pencairan extends Model
{
    protected $fillable = [
        'kode_pencairan', 'penitip_id', 'jumlah', 'tgl_pencairan', 
        'metode', 'status', 'keterangan', 'admin_id'
    ];

    public function penitip()
    {
        return $this->belongsTo(Penitip::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
