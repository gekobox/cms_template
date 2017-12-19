<?php
namespace App\Classes\Factories;

use App\Models\Supplier;

class SupplierFactory extends ResourceFactoryAbstract{
    /**
     * Create and return an instance of App\Models\Supplier model
     * @return Supplier
     */
    public static function make(){
        return new Supplier;
    }
    
}

