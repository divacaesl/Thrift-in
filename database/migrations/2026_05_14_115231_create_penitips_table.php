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
        Schema::create('penitips', function (Blueprint $table) {
            $table->id();
            $table->string('kode_penitip', 20)->unique();
            $table->string('nama', 100);
            $table->string('no_hp', 20);
            $table->string('email', 100)->nullable();
            $table->text('alamat')->nullable();
            $table->string('nama_bank', 50)->nullable();
            $table->string('no_rekening', 30)->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penitips');
    }
};
