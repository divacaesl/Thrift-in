<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi', 20)->unique();
            $table->foreignId('barang_id')->constrained('barangs');
            $table->string('nama_pembeli', 100);
            $table->string('no_hp_pembeli', 20);
            $table->decimal('harga_jual', 12, 2);
            $table->decimal('komisi_persen', 5, 2)->default(20.00);
            $table->decimal('komisi_nominal', 12, 2);
            $table->decimal('hasil_penitip', 12, 2);
            $table->enum('metode_bayar', ['tunai', 'transfer'])->default('tunai');
            $table->timestamp('tgl_transaksi')->useCurrent();
            $table->foreignId('kasir_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
