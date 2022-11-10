<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');

            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('store_id')->on('stores')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->string('product')->nullable();
            $table->integer('qty')->default(0);


            $table->tinyInteger('is_inv')->default(0);
            $table->tinyInteger('is_available')->default(1);
            $table->double('product_price')->default(0);
            $table->string('product_img_path')->nullable();

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
        Schema::dropIfExists('products');
    }
}
