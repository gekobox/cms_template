<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPermissionsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_roles', function($table){
            $table->increments('id');
            $table->string('name');
        });
        Schema::create('models', function($table){
            $table->increments('id');
            $table->string('name');
        });
        Schema::create('user_role_permissions', function($table){
            $table->increments('id');
            $table->integer('user_role_id')->unsigned()->nullable();
            $table->integer('model_id')->unsigned()->nullable();            
            $table->boolean('read_action')->default(false);
            $table->boolean('save_action')->default(false);
            $table->boolean('delete_action')->default(false);
            $table->foreign('user_role_id')->references('id')->on('user_roles')
                    ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('model_id')->references('id')->on('models')
                    ->onDelete('cascade')->onUpdate('cascade');
        });
        
        Schema::table('users', function($table){
            $table->integer('user_role_id')->unsigned()->nullable()->after('id');
            $table->foreign('user_role_id')->references('id')->on('user_roles')
                    ->onDelete('restrict')->onUpdate('restrict');
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
