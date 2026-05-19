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
        // 1. Update penitips table
        Schema::table('penitips', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('logo_toko', 255)->default('default_logo.png');
            $table->string('banner_toko', 255)->default('default_banner.png');
            $table->text('deskripsi_toko')->nullable();
            $table->string('ktp', 255)->nullable();
            $table->string('selfie', 255)->nullable();
            $table->boolean('is_verified')->default(false);
            $table->decimal('saldo', 12, 2)->default(0);
            $table->text('auto_reply_message')->nullable();
            $table->boolean('is_auto_reply_enabled')->default(false);
        });

        // 2. Update barangs table
        Schema::table('barangs', function (Blueprint $table) {
            $table->string('video', 255)->nullable();
            $table->string('material', 100)->nullable();
            $table->integer('berat')->default(0); // weight in grams
            $table->string('tags', 255)->nullable(); // e.g. "Vintage,Original,Rare Item"
            $table->string('bukti_keaslian', 255)->nullable();
            $table->string('invoice_keaslian', 255)->nullable();
            $table->string('sertifikat_keaslian', 255)->nullable();
            $table->string('lama_penggunaan', 50)->nullable();
            $table->string('frekuensi_penggunaan', 50)->nullable();
            $table->text('defect_description')->nullable();
            $table->integer('viewer_count')->default(0);
            $table->integer('favorite_count')->default(0);
        });

        // 3. Update ulasans table
        Schema::table('ulasans', function (Blueprint $table) {
            $table->text('balasan_penjual')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ulasans', function (Blueprint $table) {
            $table->dropColumn(['balasan_penjual']);
        });

        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn([
                'video', 'material', 'berat', 'tags', 'bukti_keaslian',
                'invoice_keaslian', 'sertifikat_keaslian', 'lama_penggunaan',
                'frekuensi_penggunaan', 'defect_description', 'viewer_count', 'favorite_count'
            ]);
        });

        Schema::table('penitips', function (Blueprint $table) {
            $table->dropColumn([
                'user_id', 'logo_toko', 'banner_toko', 'deskripsi_toko',
                'ktp', 'selfie', 'is_verified', 'saldo', 'auto_reply_message', 'is_auto_reply_enabled'
            ]);
        });
    }
};
