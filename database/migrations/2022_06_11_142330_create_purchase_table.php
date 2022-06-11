<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->comment('采购员id');
            $table->unsignedInteger('supplier_id')->comment('供应商id');
            $table->string('remark')->comment('备注');
            $table->unsignedTinyInteger('status')->default(0);
            $table->date('deadline_at')->comment('截止时间');
            $table->date('complete_at', 0)->nullable()->comment('完成时间');
            $table->index('supplier_id');
            $table->index('user_id');
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
        Schema::dropIfExists('purchase');
    }
}
