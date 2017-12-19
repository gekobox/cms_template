<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("payment_status", function($table){
            $table->increments("id");
            $table->string("status");
            $table->timestamps();
        });
        Schema::create("payment_types", function($table){
            $table->increments("id");
            $table->string("type");
            $table->boolean("is_active")->default(false);
            $table->timestamps();
        });
        Schema::create('payments', function($table){
            $table->increments("id");
            $table->integer("order_id")->unsigned();
            $table->integer("payment_status_id")->unsigned();
            $table->integer("payment_type_id")->unsigned();
            $table->decimal("amount");
            $table->foreign("order_id")->references("id")->on("orders")
                    ->onDelete("restrict")->onUpdate("cascade");
            
            $table->foreign("payment_status_id")->references("id")->on("payment_status")
                    ->onDelete("restrict")->onUpdate("cascade");
            
            $table->foreign("payment_type_id")->references("id")->on("payment_types")
                    ->onDelete("restrict")->onUpdate("cascade");
            
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
