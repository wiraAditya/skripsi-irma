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
            $table->integer('table_id');
            $table->string('code');
            $table->datetime('date');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->integer('total_price')->nullable();
            $table->integer('payment_type')->nullable();
            $table->text('payment_url')->nullable();
            $table->integer('payment_status')->default(1);
            $table->timestamps();
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
