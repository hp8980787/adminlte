<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('name')->comment('jianjie1');
            $table->string('category');
            $table->string('brand');
            $table->string('cover_img');
            $table->string('imgs')->nullable();
            $table->string('dl')->nullable();
            $table->string('dy')->nullable();
            $table->string('type')->nullable();
            $table->string('size')->nullable();
            $table->string('bzq')->nullable();
            $table->decimal('price_eu',10,2)->nullable();
            $table->decimal('price_us',10,2)->nullable();
            $table->decimal('price_uk',10,2)->nullable();
            $table->decimal('price_jp',10,2)->nullable();
            $table->unsignedTinyInteger('status')->default(0);
            $table->string('replace')->comment('替代商品');
            $table->text('description')->comment('comp');
            $table->unsignedInteger('stock')->default(0);
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
        Schema::dropIfExists('admin_products');
    }
    
}
