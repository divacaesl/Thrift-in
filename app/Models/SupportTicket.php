<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_tiket',
        'user_id',
        'subjek',
        'deskripsi',
        'prioritas',
        'status',
        'handled_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function replies()
    {
        return $this->hasMany(TicketReply::class);
    }
}
