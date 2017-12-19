<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDatatypesTranslations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $dutchLang= new \App\Models\Language;
        $dutchLang->code= 'nl';
        $dutchLang->name= 'Nederlands';
        $dutchLang->save();
        
        $DataType= App\Models\DataType::where('code','string_value')->first();
        $DataTypeTranslation= new App\Models\DataTypeTranslation();
        $DataTypeTranslation->language_id= $dutchLang->id;
        $DataTypeTranslation->data_type_id= $DataType->id;
        $DataTypeTranslation->name= "String";
        $DataTypeTranslation->save();
        
        $DataType= App\Models\DataType::where('code','integer_value')->first();
        $DataTypeTranslation= new App\Models\DataTypeTranslation();
        $DataTypeTranslation->language_id= $dutchLang->id;
        $DataTypeTranslation->data_type_id= $DataType->id;
        $DataTypeTranslation->name= "Integer";
        $DataTypeTranslation->save();
        
        $DataType= App\Models\DataType::where('code','decimal_value')->first();
        $DataTypeTranslation= new App\Models\DataTypeTranslation();
        $DataTypeTranslation->language_id= $dutchLang->id;
        $DataTypeTranslation->data_type_id= $DataType->id;
        $DataTypeTranslation->name= "Decimal";
        $DataTypeTranslation->save();
        
        $DataType= App\Models\DataType::where('code','longtext_value')->first();
        $DataTypeTranslation= new App\Models\DataTypeTranslation();
        $DataTypeTranslation->language_id= $dutchLang->id;
        $DataTypeTranslation->data_type_id= $DataType->id;
        $DataTypeTranslation->name= "Longtext";
        $DataTypeTranslation->save();
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
