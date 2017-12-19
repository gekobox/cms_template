<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentStatusAndTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("resource_translations", function($table){
            $table->integer("resource_instance_id")->unsigned()->after("resource_id")->nullable();
        });

        $resource= new App\Models\Resource();
        $resource->name="paymentType";
        $resource->save();
        
        $resource= new App\Models\Resource();
        $resource->name="paymentStatus";
        $resource->save();
        
        $paymentType= new \App\Models\PaymentType();
        $paymentType->type= 'maestro';
        $paymentType->name= 'Maestro';
        $paymentType->save();
        
        $paymentType= new \App\Models\PaymentType();
        $paymentType->type= 'cash';
        $paymentType->name= 'Cash';
        $paymentType->save();
        
        $paymentType= new \App\Models\PaymentType();
        $paymentType->type= 'visa_mastercard';
        $paymentType->name= 'Visa/Mastercard';
        $paymentType->save();
        
        $paymentStatus= new App\Models\PaymentStatus();
        $paymentStatus->status="paid";
        $paymentStatus->name="Paid";
        $paymentStatus->save();
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
