<?php
namespace App\Classes\Factories;

use App\Models\Product;

class ProductFactory extends ResourceFactoryAbstract{
    /**
     * Create and return an instance of App\Models\Product model
     * @return Product
     */
    public static function make(){
        return new Product;
    }
        
}

