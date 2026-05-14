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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang', 20)->unique();
            $table->foreignId('penitip_id')->constrained('penitips')->cascadeOnDelete();
            $table->foreignId('kategori_id')->constrained('kategoris');
            $table->string('nama_barang', 150);
            $table->text('deskripsi')->nullable();
            $table->enum('kondisi', ['baru', 'seperti_baru', 'bekas_layak', 'bekas']);
            $table->decimal('harga_jual', 12, 2);
            $table->string('foto', 255)->default('default.jpg');
            $table->enum('status', ['diterima', 'diverifikasi', 'ditampilkan', 'terjual', 'dicairkan', 'ditarik'])->default('diterima');
            $table->date('tgl_masuk');
            $table->date('tgl_terjual')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
