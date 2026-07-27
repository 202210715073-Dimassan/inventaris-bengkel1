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
        Schema::table('products', function (Blueprint $table) {
            $table->string('large_unit')->nullable()->after('unit')->comment('Satuan besar opsional (contoh: dus, box)');
            $table->integer('unit_conversion')->default(1)->after('large_unit')->comment('Konversi isi (contoh: 24 botol per dus)');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->string('transaction_unit')->nullable()->after('quantity')->comment('Satuan transaksi yang digunakan');
            $table->integer('original_quantity')->nullable()->after('transaction_unit')->comment('Jumlah unit asli sebelum dikonversi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['large_unit', 'unit_conversion']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['transaction_unit', 'original_quantity']);
        });
    }
};
