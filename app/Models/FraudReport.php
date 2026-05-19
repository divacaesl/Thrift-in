<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FraudReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_id',
        'dilaporkan_oleh',
        'tipe_laporan',
        'deskripsi_laporan',
        'ai_confidence_score',
        'status',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function pelapor()
    {
        return $this->belongsTo(User::class, 'dilaporkan_oleh');
    }
}
