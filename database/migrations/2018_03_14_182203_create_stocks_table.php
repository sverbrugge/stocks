<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ticker')->unique()->nullable();
            $table->string('name');
            $table->integer('currency_id')->unsigned();
            $table->integer('exchange_id')->unsigned();
            $table->timestamps();
            
            $table->unique([ 'ticker', 'exchange_id' ]);
            $table->unique([ 'name', 'exchange_id' ]);
            
            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->foreign('exchange_id')->references('id')->on('exchanges');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stocks');
    }
}
