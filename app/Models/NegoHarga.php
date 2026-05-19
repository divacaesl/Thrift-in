<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NegoHarga extends Model
{
    protected $table = 'nego_hargas';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
