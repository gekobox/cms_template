<?php
namespace App\Classes\Factories;

use App\Models\ProductImage;

class ProductImageFactory extends ResourceFactoryAbstract{
    /**
     * Create and return an instance of App\Models\ProductImage model
     * @return ProductImage
     */
    public static function make(){
        return new ProductImage;
    }
        
}

