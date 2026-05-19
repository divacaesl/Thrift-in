<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $guarded = [];

    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    public function seller()
    {
        return $this->belongsTo(Penitip::class, 'penitip_id');
    }
}
