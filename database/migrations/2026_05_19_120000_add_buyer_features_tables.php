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
        // 1. Update users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('no_hp', 20)->nullable();
            $table->string('foto_profil', 255)->default('default_profile.jpg');
            $table->string('metode_bayar_favorit', 50)->nullable();
            $table->string('preferred_language', 5)->default('id');
            $table->string('theme_mode', 10)->default('light');
        });

        // 2. Update barangs table
        Schema::table('barangs', function (Blueprint $table) {
            $table->string('brand', 100)->nullable();
            $table->string('ukuran', 20)->nullable();
            $table->string('warna', 50)->nullable();
            $table->integer('stok')->default(1);
            $table->string('lokasi', 100)->nullable();
            $table->text('multiple_fotos')->nullable();
            $table->boolean('is_flash_sale')->default(false);
            $table->integer('diskon_persen')->default(0);
        });

        // 3. Create vouchers table first so it can be referenced
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('kode_voucher', 50)->unique();
            $table->decimal('diskon', 12, 2);
            $table->decimal('min_beli', 12, 2)->default(0);
            $table->string('status', 20)->default('aktif');
            $table->timestamps();
        });

        // 4. Create alamat_pengirimans table
        Schema::create('alamat_pengirimans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('label', 50); // Rumah, Kantor, dll
            $table->string('nama_penerima', 100);
            $table->string('no_hp', 20);
            $table->text('alamat_lengkap');
            $table->string('kota', 100);
            $table->string('kode_pos', 10);
            $table->boolean('is_utama')->default(false);
            $table->timestamps();
        });

        // 5. Update transaksis table
        Schema::table('transaksis', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status_pesanan', 50)->default('menunggu_pembayaran');
            $table->string('bukti_transfer', 255)->nullable();
            $table->text('catatan')->nullable();
            $table->string('ekspedisi', 50)->nullable();
            $table->string('no_resi', 50)->nullable();
            $table->decimal('ongkir', 12, 2)->default(0);
            $table->foreignId('alamat_pengiriman_id')->nullable()->constrained('alamat_pengirimans')->nullOnDelete();
            // In case there is no kasir (buyer checkout online), kasir_id can be nullable.
            // We modify the DB but in SQLite we can just specify nullable if we drop constraint or create field.
            // Since kasir_id is already in the table, we'll allow null in application level if possible.
        });

        // 6. Create carts table
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barangs')->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->boolean('is_saved_for_later')->default(false);
            $table->timestamps();
        });

        // 7. Create wishlists table
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barangs')->cascadeOnDelete();
            $table->timestamps();
        });

        // 8. Create chats table
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            // Since we can chat with penitip, we can chat with a user representing penitip or admin.
            // Let's reference users table for receiver_id.
            $table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('barang_id')->nullable()->constrained('barangs')->nullOnDelete();
            $table->text('pesan');
            $table->string('gambar', 255)->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        // 9. Create nego_hargas table
        Schema::create('nego_hargas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barangs')->cascadeOnDelete();
            $table->decimal('harga_tawaran', 12, 2);
            $table->string('status', 20)->default('pending'); // pending, diterima, ditolak
            $table->timestamps();
        });

        // 10. Create ulasans table
        Schema::create('ulasans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barangs')->cascadeOnDelete();
            $table->integer('rating'); // 1 to 5
            $table->text('ulasan')->nullable();
            $table->string('foto', 255)->nullable();
            $table->integer('respon_rate')->nullable(); // 1 to 5
            $table->integer('kirim_rate')->nullable();   // 1 to 5
            $table->integer('sesuai_rate')->nullable();  // 1 to 5
            $table->timestamps();
        });

        // 11. Create follows table
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('follower_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('penitip_id')->constrained('penitips')->cascadeOnDelete();
            $table->timestamps();
        });

        // 12. Create recently_vieweds table
        Schema::create('recently_vieweds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barangs')->cascadeOnDelete();
            $table->timestamp('viewed_at')->useCurrent();
        });

        // 13. Create notifikasis table
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('judul', 150);
            $table->text('pesan');
            $table->string('tipe', 50); // chat, transaksi, promo
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        // 14. Create complaints table
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksis')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('alasan');
            $table->string('foto', 255)->nullable();
            $table->string('status', 50)->default('pending'); // pending, diproses, selesai, ditolak
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
        Schema::dropIfExists('notifikasis');
        Schema::dropIfExists('recently_vieweds');
        Schema::dropIfExists('follows');
        Schema::dropIfExists('ulasans');
        Schema::dropIfExists('nego_hargas');
        Schema::dropIfExists('chats');
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('alamat_pengirimans');
        Schema::dropIfExists('vouchers');

        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'status_pesanan', 'bukti_transfer', 'catatan', 'ekspedisi', 'no_resi', 'ongkir', 'alamat_pengiriman_id']);
        });

        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn(['brand', 'ukuran', 'warna', 'stok', 'lokasi', 'multiple_fotos', 'is_flash_sale', 'diskon_persen']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['no_hp', 'foto_profil', 'metode_bayar_favorit', 'preferred_language', 'theme_mode']);
        });
    }
};
