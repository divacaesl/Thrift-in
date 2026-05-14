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
        Schema::create('pencairans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pencairan', 20)->unique();
            $table->foreignId('penitip_id')->constrained('penitips');
            $table->decimal('jumlah', 12, 2);
            $table->date('tgl_pencairan');
            $table->enum('metode', ['transfer', 'tunai'])->default('transfer');
            $table->enum('status', ['pending', 'diproses', 'selesai'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->foreignId('admin_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pencairans');
    }
};
