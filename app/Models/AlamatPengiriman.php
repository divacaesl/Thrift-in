<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlamatPengiriman extends Model
{
    protected $table = 'alamat_pengirimans';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
