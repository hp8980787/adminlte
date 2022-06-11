<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseItemsTabel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->comment('采购产品id');
            $table->string('explain')->comment('说明')->nullable();
            $table->decimal('price', 10, 2)->comment('采购价格');
            $table->unsignedInteger('quantity');
            $table->decimal('amount', 10, 2)->comment('小计');
            $table->foreign('product_id')->references('id')->on('purchase')->onDelete('cascade');
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
        Schema::dropIfExists('purchase_items');
    }
}
