<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDatatypesValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $dataTypes=['string_value','integer_value','decimal_value','longtext_value'];
        foreach($dataTypes as $type){
            $DataType= new App\Models\DataType();
            $DataType->code= $type;
            $DataType->save();
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
