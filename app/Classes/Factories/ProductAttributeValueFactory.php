<?php
namespace App\Classes\Factories;

use App\Models\ProductAttributeValue;

class ProductAttributeValueFactory extends ResourceFactoryAbstract{
    /**
     * Create and return an instance of App\Models\ProductAttributeValue model
     * @return ProductAttributeValue
     */
    public static function make(){
        return new ProductAttributeValue;
    }
        
}

