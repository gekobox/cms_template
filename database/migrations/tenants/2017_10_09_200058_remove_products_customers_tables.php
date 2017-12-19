<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveProductsCustomersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('product_supplier');
        Schema::drop('product_images');
        Schema::drop('product_attribute_values');
        $orderLines= App\Models\OrderLine::all();
        foreach ($orderLines as $ol){
            $ol->delete();
        }
        Schema::table('order_lines', function($table){
            $table->dropForeign("order_lines_product_id_foreign");
        });
        Schema::drop('products');
        
        Schema::drop('relation_contacts');
        Schema::drop('relation_addresses');
        
        Schema::table('orders', function($table){
            $table->dropForeign("orders_relation_id_foreign");
        });
        Schema::drop('relations');
        
        
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
