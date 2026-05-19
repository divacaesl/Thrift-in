<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function alamatPengirimans()
    {
        return $this->hasMany(AlamatPengiriman::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class)->latest();
    }

    public function orders()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function penitip()
    {
        return $this->hasOne(Penitip::class);
    }
}
