<?php
namespace App\Classes\Factories;

use App\Models\ProductSupplier;

class ProductSupplierFactory extends ResourceFactoryAbstract{
    /**
     * Create and return an instance of App\Models\ProductSupplier model
     * @return ProductSupplier
     */
    public static function make(){
        return new ProductSupplier;
    }
        
}

