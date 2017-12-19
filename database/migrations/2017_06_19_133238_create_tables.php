<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {    
        Schema::create('languages', function($table){
           $table->increments('id');           
           $table->string('code');
           $table->string('name');
           $table->timestamps();
        });
        
        Schema::create('data_types', function($table){
           $table->increments('id');           
           $table->string('code');
           $table->timestamps();
        });
        
        Schema::create('suppliers', function($table){
           $table->increments('id');           
           $table->string('name');
           $table->longText('notes')->nullable();
           $table->timestamps();
        });
        
        Schema::create('address_types', function($table){
           $table->increments('id');           
           $table->string('name');           
           $table->timestamps();
        });
        
        Schema::create('countries', function($table){
           $table->increments('id');           
           $table->string('name');           
           $table->timestamps();
        });
        
        Schema::create('attributes', function($table){
           $table->increments('id');
           $table->integer('data_type_id')->unsigned();
           $table->string('name');
           $table->foreign('data_type_id')->references('id')->on('data_types')
                   ->onDelete('restrict')->onUpdate('cascade');
           $table->index('data_type_id');
           $table->timestamps();
        });
         
         Schema::create('data_type_translations', function($table){
           $table->increments('id');           
           $table->integer('data_type_id')->unsigned();
           $table->integer('language_id')->unsigned();           
           $table->string('name');
           $table->foreign('data_type_id')->references('id')->on('data_types')
                   ->onDelete('cascade')->onUpdate('cascade');
           $table->index('data_type_id');
           $table->foreign('language_id')->references('id')->on('languages')
                   ->onDelete('cascade')->onUpdate('cascade');
           $table->index('language_id');
           $table->timestamps();
        });
        
        
        Schema::create('supplier_addresses', function($table){
           $table->increments('id');           
           $table->integer('supplier_id')->unsigned();
           $table->integer('address_type_id')->unsigned();
           $table->integer('country_id')->unsigned();
           $table->string('street')->nullable();
           $table->integer('house_number')->nullable();
           $table->string('house_number_addition')->nullable();
           $table->string('postal_code')->nullable();
           $table->string('city')->nullable();
           $table->foreign('supplier_id')->references('id')->on('suppliers')
                   ->onDelete('cascade')->onUpdate('cascade');
           $table->index('supplier_id');
           $table->foreign('address_type_id')->references('id')->on('address_types')
                   ->onDelete('restrict')->onUpdate('cascade');
           $table->index('address_type_id');
           $table->foreign('country_id')->references('id')->on('countries')
                   ->onDelete('restrict')->onUpdate('cascade');
           $table->index('country_id');
           $table->timestamps();
        });
        
        Schema::create('supplier_contacts', function($table){
           $table->increments('id');           
           $table->integer('supplier_id')->unsigned();           
           $table->string('first_name')->nullable();
           $table->string('last_name')->nullable();
           $table->string('phone')->nullable();
           $table->string('email')->nullable();
           $table->foreign('supplier_id')->references('id')->on('suppliers')
                   ->onDelete('cascade')->onUpdate('cascade');
           $table->index('supplier_id');
           $table->timestamps();
        });
                
        Schema::create('products', function($table){
            $table->increments('id');
            $table->integer('supplier_id')->unsigned();
            $table->string('name');
            $table->string('sku');
            $table->string('ean')->nullable();
            $table->string('supplier_code')->nullable();
            $table->decimal('price',10,2)->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers')
                    ->onDelete('restrict')->onUpdate('cascade');
            $table->index('supplier_id');
            $table->timestamps();
        });
        
        Schema::create('product_images', function($table){
           $table->increments('id');
           $table->integer('product_id')->unsigned();
           $table->integer('seq')->nullable();
           $table->string('image');
           $table->foreign('product_id')->references('id')->on('products')
                   ->onDelete('cascade')->onUpdate('cascade');
            $table->index('product_id');
           $table->timestamps();
        });
        
        Schema::create('product_attribute_values', function($table){
           $table->increments('id');
           $table->integer('product_id')->unsigned();
           $table->integer('attribute_id')->unsigned();
           $table->string('string_value')->nullable();
           $table->integer('integer_value')->nullable();
           $table->decimal('decimal_value',10,2)->nullable();
           $table->longText('longtext_value')->nullable();
           $table->foreign('product_id')->references('id')->on('products')
                   ->onDelete('cascade')->onUpdate('cascade');
            $table->index('product_id');
           $table->foreign('attribute_id')->references('id')->on('attributes')
                   ->onDelete('cascade')->onUpdate('cascade');
           $table->index('attribute_id');
           $table->timestamps();
        });
        
        
        
        Schema::create('address_type_translations', function($table){
           $table->increments('id');           
           $table->integer('address_type_id')->unsigned();
           $table->integer('language_id')->unsigned();           
           $table->string('name');
           $table->foreign('address_type_id')->references('id')->on('address_types')
                   ->onDelete('cascade')->onUpdate('cascade');
           $table->index('address_type_id');
           $table->foreign('language_id')->references('id')->on('languages')
                   ->onDelete('cascade')->onUpdate('cascade');
           $table->index('language_id');
           $table->timestamps();
        });
        
        Schema::create('country_translations', function($table){
           $table->increments('id');           
           $table->integer('country_id')->unsigned();
           $table->integer('language_id')->unsigned();           
           $table->string('name');
           $table->foreign('country_id')->references('id')->on('countries')
                   ->onDelete('cascade')->onUpdate('cascade');
           $table->index('country_id');
           $table->foreign('language_id')->references('id')->on('languages')
                   ->onDelete('cascade')->onUpdate('cascade');
           $table->index('language_id');
           $table->timestamps();
        });
        
        Schema::create('relations', function($table){
           $table->increments('id');           
           $table->tinyInteger('is_customer');
           $table->tinyInteger('is_business');
           $table->string('name');
           $table->longText('notes')->nullable();
           $table->timestamps();
        });
        
        Schema::create('relation_contacts', function($table){
           $table->increments('id');           
           $table->integer('relation_id')->unsigned();
           $table->string('first_name')->nullable();
           $table->string('last_name')->nullable();
           $table->string('phone')->nullable();
           $table->string('email')->nullable();
           $table->foreign('relation_id')->references('id')->on('relations')
                   ->onDelete('cascade')->onUpdate('cascade');
           $table->index('relation_id');           
           $table->timestamps();
        });
        
        Schema::create('relation_addresses', function($table){
           $table->increments('id');           
           $table->integer('relation_id')->unsigned();
           $table->integer('address_type_id')->unsigned();
           $table->integer('country_id')->unsigned();
           $table->string('street')->nullable();
           $table->integer('house_number')->nullable();
           $table->string('house_number_addition')->nullable();
           $table->string('postal_code')->nullable();
           $table->string('city')->nullable();
           $table->foreign('relation_id')->references('id')->on('relations')
                   ->onDelete('cascade')->onUpdate('cascade');
           $table->index('relation_id');
           $table->foreign('address_type_id')->references('id')->on('address_types')
                   ->onDelete('restrict')->onUpdate('cascade');
           $table->index('address_type_id');
           $table->foreign('country_id')->references('id')->on('countries')
                   ->onDelete('restrict')->onUpdate('cascade');
           $table->index('country_id');
           $table->timestamps();
        });
        
        Schema::create("resources", function($table){
            $table->increments("id");
            $table->string("name");
            $table->timestamps();
        });
        
        Schema::create("resource_translations", function($table){
            $table->increments("id");
            $table->integer("resource_id")->unsigned();
            $table->integer("language_id")->unsigned();
            $table->string("field_name");
            $table->string("field_value");
            
            $table->foreign("resource_id")->references("id")->on("resources")
                    ->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("language_id")->references("id")->on("languages")
                    ->onDelete("cascade")->onUpdate("cascade");
            
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
        Schema::drop('product_images');
        Schema::drop('product_attribute_values');
        Schema::drop('data_type_translations');
        Schema::drop('attributes');
        Schema::drop('data_types');
        Schema::drop('supplier_addresses');
        Schema::drop('supplier_contacts');        
        Schema::drop('products');        
        Schema::drop('address_type_translations');
        Schema::drop('country_translations');
        Schema::drop('relation_contacts');
        Schema::drop('relation_addresses');
        Schema::drop('address_types');        
        Schema::drop('countries');        
        Schema::drop('relations');  
        Schema::drop('languages');
        Schema::drop('suppliers');

    }
}
