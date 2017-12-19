<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEcommercePlatformTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("ecommerce_platforms", function($table){
            $table->increments("id");
            $table->string("platform_code");
            $table->string("platform_name");
            $table->boolean("active")->default(false);
            $table->timestamps();
        });
        
        Schema::create("ecommerce_platform_settings", function($table){
            $table->increments("id");
            $table->integer("platform_id")->unsigned();
            $table->string("attribute");
            $table->string("value");
            $table->foreign("platform_id")->references("id")->on("ecommerce_platforms");
            $table->timestamps();
        });
        
        //insert magento settings
        $magento= new App\Models\EcommercePlatform();
        $magento->platform_code="magento";
        $magento->platform_name="Magento";
        $magento->active=true;
        $magento->save();
        
        $settings = array(
            'base_url'        => '',
            'consumer_key'    => '',
            'consumer_secret' => '',
            'token'           => '',
            'token_secret'    => '',
        );
        
        foreach($settings as $attr => $value){
            $setting= new \App\Models\EcommercePlatformSetting();
            $setting->platform_id= $magento->id;
            $setting->attribute= $attr;
            $setting->value= $value;
            $setting->save();
        }
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
