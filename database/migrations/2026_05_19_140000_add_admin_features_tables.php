<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. System Settings for Global Configurations
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general'); // general, payment, shipping, security
            $table->timestamps();
        });

        // 2. Banners & Promos
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('gambar');
            $table->string('link_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // 3. Support Tickets / Komplain User
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('kode_tiket')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('subjek');
            $table->text('deskripsi');
            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi', 'kritis'])->default('sedang');
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');
            $table->foreignId('handled_by')->nullable()->constrained('users')->onDelete('set null'); // Admin ID
            $table->timestamps();
        });

        // 4. Ticketing Replies (Live Chat Support Simulation)
        Schema::create('ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade'); // Bisa User atau Admin
            $table->text('pesan');
            $table->string('attachment')->nullable();
            $table->timestamps();
        });

        // 5. Fraud Reports & AI Detection Logs
        Schema::create('fraud_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->nullable()->constrained('barangs')->onDelete('cascade');
            $table->foreignId('dilaporkan_oleh')->nullable()->constrained('users')->onDelete('set null'); // User pelapor atau AI
            $table->string('tipe_laporan'); // barang_palsu, harga_tidak_wajar, scam_seller, spam
            $table->text('deskripsi_laporan');
            $table->decimal('ai_confidence_score', 5, 2)->nullable(); // e.g. 95.50
            $table->enum('status', ['pending', 'investigating', 'action_taken', 'dismissed'])->default('pending');
            $table->timestamps();
        });

        // 6. Admin Activity Logs (Security Auditing)
        Schema::create('admin_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->string('action_type'); // login, suspend_user, approve_payout, delete_product
            $table->text('description');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
        
        // Add status to Barangs table for moderation
        Schema::table('barangs', function (Blueprint $table) {
            // Already has 'status' enum ('ditampilkan', 'disembunyikan', 'dihapus'). 
            // We'll add a 'moderation_status' to separate moderation logic from visibility.
            $table->enum('moderation_status', ['pending', 'approved', 'rejected', 'flagged'])->default('approved')->after('status');
            $table->string('moderation_notes')->nullable()->after('moderation_status');
        });
        
        // Enhance Transaksi with Dispute states
        Schema::table('transaksis', function (Blueprint $table) {
            // Existing statuses: 'menunggu_pembayaran', 'diproses', 'dikirim', 'sampai', 'refund'
            // We'll add 'dispute' and 'refund_processed' to the ENUM. Since SQLite ENUM modification is hard,
            // we will just use string column for dispute status to be safe.
            $table->string('dispute_status')->nullable()->after('status_pesanan'); // open, resolved_buyer, resolved_seller
            $table->text('dispute_notes')->nullable()->after('dispute_status');
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['dispute_status', 'dispute_notes']);
        });
        
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn(['moderation_status', 'moderation_notes']);
        });

        Schema::dropIfExists('admin_activity_logs');
        Schema::dropIfExists('fraud_reports');
        Schema::dropIfExists('ticket_replies');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('banners');
        Schema::dropIfExists('system_settings');
    }
};
