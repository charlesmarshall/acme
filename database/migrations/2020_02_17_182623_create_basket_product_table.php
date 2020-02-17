<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBasketProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('basket_product')) {
            Schema::create('basket_product', function (Blueprint $table) {
                $table->bigIncrements('id');

                $table->unsignedBigInteger('basket_id');
                $table->foreign('basket_id')->references('id')->on('basket');
                
                $table->unsignedBigInteger('product_id');
                $table->foreign('product_id')->references('id')->on('product');
                
                $table->string("code");
                $table->decimal('unit_price', 8, 2);
                $table->decimal('discount', 8, 2)->default(0.0);

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('basket_product');
    }
}
