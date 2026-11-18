<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Ubah enum menjadi string agar metode 'midtrans' bisa disimpan
            // di MySQL maupun SQLite.
            $table->string('payment_method', 20)->default('transfer')->change();
            $table->string('snap_token')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('payment_type')->nullable();
            $table->timestamp('paid_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['snap_token', 'payment_status', 'payment_type', 'paid_at']);
            $table->enum('payment_method', ['transfer', 'cod'])->default('transfer')->change();
        });
    }
};
