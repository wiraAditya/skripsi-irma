
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('refund_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('refund_id');
            $table->unsignedBigInteger('order_detail_id');
            $table->integer('quantity');
            $table->integer('refund_amount');
            $table->text('reason')->nullable();
            $table->timestamps();

            $table->index('refund_id');
            $table->index('order_detail_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('refund_items');
    }
};