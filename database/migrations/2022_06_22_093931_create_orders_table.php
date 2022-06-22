<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('trans_id');
            $table->string('order_number');
            $table->decimal('total', 10, 2);
            $table->decimal('total_usd', 10, 2);
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->string('postal');
            $table->string('country');
            $table->string('state')->nullable();
            $table->string('city');
            $table->string('street1');
            $table->string('street2')->nullable();
            $table->string('currency');
            $table->string('description');
            $table->string('product_code')->nullable();
            $table->unsignedTinyInteger('status')->default(0);
            $table->index('trans_id');
            $table->index('order_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
