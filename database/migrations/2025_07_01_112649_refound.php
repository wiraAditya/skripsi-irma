<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('refund_amount');
            $table->text('reason')->nullable();
            $table->string('status')->default('approved');
            $table->string('refund_method'); // 'cash' or 'transfer'
            $table->timestamps();

            $table->index('order_id');
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('refunds');
    }
};