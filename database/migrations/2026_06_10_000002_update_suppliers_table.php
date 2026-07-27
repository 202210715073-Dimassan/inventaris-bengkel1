<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Memisahkan kolom 'contact' menjadi 'phone' dan 'email' pada tabel suppliers.
     */
    public function up(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            // Tambah kolom baru yang lebih spesifik
            $table->string('phone')->nullable()->after('name')->comment('Nomor telepon supplier');
            $table->string('email')->nullable()->after('phone')->comment('Email supplier');

            // Hapus kolom lama yang terlalu umum
            $table->dropColumn('contact');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('contact')->nullable()->after('name');
            $table->dropColumn(['phone', 'email']);
        });
    }
};
