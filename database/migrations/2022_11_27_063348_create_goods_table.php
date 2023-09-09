<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('customer_id')->nullable();
            $table->date('date');
            $table->bigInteger('product_id');
            $table->bigInteger('stock_id');
            $table->bigInteger('sale_id');
            $table->bigInteger('user_id');
            $table->string('seller');
            $table->string('name');
            $table->double('volume');
            $table->string('type')->nullable();
            $table->double('quantity');
            $table->string('unit');
            $table->double('price');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods');
    }
};
