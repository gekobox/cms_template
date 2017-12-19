<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts', function($table){
            $table->dropColumn(['debit_name', 'debit_iban', 'debit_bic_swift', 'debit_signature_date', 'debit_reference']);
            $table->string('payment_customer_id')->nullable();
            $table->tinyInteger('is_active')->default(0);
        });

        Schema::create('account_subscriptions', function($table){
            $table->increments('id');
            $table->integer('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('restrict')->onUpdate('cascade');
            $table->index('account_id');
            $table->string('subscription_id');
            $table->timestamps();
        });

        Schema::create('account_payments', function($table){
            $table->increments('id');
            $table->integer('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('restrict')->onUpdate('cascade');
            $table->index('account_id');
            $table->integer('account_subscription_id')->unsigned()->nullable();
            $table->foreign('account_subscription_id')->references('id')->on('account_subscriptions')->onDelete('restrict')->onUpdate('cascade');
            $table->index('account_subscription_id');
            $table->string('payment_id');
            $table->string('status')->nullable();
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
