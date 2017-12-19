<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function($table){
            $table->increments('id');
            $table->string('company');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('street');
            $table->integer('house_number');
            $table->string('house_number_addition')->nullable();
            $table->string('postal_code');
            $table->string('city');
            $table->integer('country_id')->unsigned();
            $table->string('phone')->nullable();
            $table->string('email');
            $table->string('debit_name');
            $table->string('debit_iban');
            $table->string('debit_bic_swift')->nullable();
            $table->timestamp('debit_signature_date')->nullable();
            $table->string('debit_reference')->nullable();
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
