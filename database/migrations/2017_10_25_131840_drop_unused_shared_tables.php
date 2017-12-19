<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropUnusedSharedTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop("order_lines");
        Schema::drop("payments");
        Schema::drop("payment_status");
        Schema::drop("payment_types");
        Schema::drop("orders");        
        Schema::drop("user_role_permissions");
        Schema::drop("user_roles");
        Schema::drop("ecommerce_platform_settings");
        Schema::drop("ecommerce_platforms");        
        Schema::drop("models");
        
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
