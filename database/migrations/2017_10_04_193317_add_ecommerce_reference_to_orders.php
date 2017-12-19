<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEcommerceReferenceToOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("orders", function($table){
            $table->string("ecommerce_relation_code")->nullable();
        });
        
        //add magento generic relation
        $relation= new App\Models\Relation();
        $relation->is_customer=true;
        $relation->is_business=false;
        $relation->name="magento-generic-relation";
        $relation->save();
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
