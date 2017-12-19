<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEcommerceReferenceToOrderlines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("order_lines", function($table){
            $table->string("ecommerce_code")->nullable();
        });
        
        //create default Magento product
        $product= new \App\Models\Product();
        $product->name="Magento product";
        $product->sku="magento-generic";
        $product->ean="magento-generic";
        $product->supplier_code="magento";
        $product->save();
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
