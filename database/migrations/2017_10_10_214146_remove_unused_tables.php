<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUnusedTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('attributes');
        Schema::drop('data_type_translations');
        Schema::drop('data_types');
        Schema::drop('supplier_addresses');
        Schema::drop('supplier_contacts');
        Schema::drop('suppliers');
        Schema::drop('address_types');
        Schema::drop('warehouses');

        Schema::table('accounts', function($table) {
            $table->index('country_id');
            $table->foreign("country_id")->references("id")->on("countries")->onUpdate('cascade')->onDelete('restrict');
        });

        Schema::table('orders', function($table) {
            $table->index('user_id');            
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
