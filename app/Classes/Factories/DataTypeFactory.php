<?php
namespace App\Classes\Factories;

use App\Models\DataType;

class DataTypeFactory extends ResourceFactoryAbstract{
    /**
     * Create and return an instance of App\Models\DataType model
     * @return DataType
     */
    public static function make(){
        return new DataType;
    }
        
}

