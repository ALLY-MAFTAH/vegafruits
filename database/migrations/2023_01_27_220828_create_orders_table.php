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
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->string('delivery_time');
            $table->date('delivery_date');
            $table->string('number');
            $table->double('total_amount')->nullable();
            $table->string('delivery_location');
            $table->date('served_date')->nullable();
            $table->string('served_by')->nullable();
            $table->boolean('status')->default(false);
            $table->boolean('is_paid')->default(false);
            $table->boolean('was_contacted')->default(false);
            $table->bigInteger('customer_id');
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
        Schema::dropIfExists('orders');
    }
};
