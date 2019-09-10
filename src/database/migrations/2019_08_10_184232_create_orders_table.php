<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->bigIncrements('id')->autoIncrement();
            $table->float('origin_lat', 10, 6);
            $table->float('origin_lng', 10, 6);
            $table->float('destination_lat', 10, 6);
            $table->float('destination_lng', 10, 6);
            $table->double('distance', 10, 2);
            $table->char('status', 10);
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
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
