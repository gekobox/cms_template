<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function($table){
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('relation_id')->unsigned()->nullable();
            $table->string('status')->default('open');
            $table->foreign('relation_id')->references('id')->on('relations')
                    ->onDelete('restrict')->onUpdate('restrict');
            $table->timestamps();
        });
        Schema::create('order_lines', function($table){
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('amount');
            $table->decimal('price');
            $table->decimal('discount')->nullable();
            $table->decimal('note')->nullable();
            $table->decimal('vat_rate')->default(0);
            $table->foreign('order_id')->references('id')->on('orders')
                    ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_id')->references('id')->on('products')
                    ->onDelete('restrict')->onUpdate('restrict');
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
        //
    }
}
