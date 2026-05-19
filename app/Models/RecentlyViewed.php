<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecentlyViewed extends Model
{
    protected $table = 'recently_vieweds';
    protected $guarded = [];
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'viewed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
