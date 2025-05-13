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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('meja_id');
            $table->string('nama');
            $table->datetime('tanggal');
            $table->integer('subtotal');
            $table->integer('tax');
            $table->string('payment_method'); //method_cash, method_digital
            $table->string('transaction_code'); 
            $table->string('catatan');
            $table->string('status'); //status_waiting_cash ,status_paid ,status_process ,status_done ,status_canceled
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
