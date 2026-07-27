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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('stock')->default(0);
            $table->integer('lead_time')->default(0)->comment('Average Lead Time (days)');
            $table->integer('max_lead_time')->default(0)->comment('Max Lead Time (days)');
            $table->decimal('average_usage', 8, 2)->default(0)->comment('Average Usage per day');
            $table->integer('max_usage')->default(0)->comment('Max Usage per day');
            $table->decimal('ss_value', 10, 2)->default(0);
            $table->decimal('rop_value', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
