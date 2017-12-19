<?php
namespace App\Classes\Factories;

use App\Models\Attribute;

class AttributeFactory extends ResourceFactoryAbstract{
    /**
     * Create and return an instance of App\Models\Attribute model
     * @return Attribute
     */
    public static function make(){
        return new Attribute;
    }
        
}

