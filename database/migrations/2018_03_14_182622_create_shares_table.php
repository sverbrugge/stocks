<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSharesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shares', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stock_id')->unsigned();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->date('transacted_at')->nullable();
            $table->smallInteger('amount')->unsigned();
            $table->decimal('price', 8, 4);
            $table->decimal('exchange_rate', 8, 4);
            $table->timestamps();
            
            $table->foreign('stock_id')->references('id')->on('stocks');
            $table->foreign('parent_id')->references('id')->on('shares');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shares');
    }
}
