<?php
namespace App\Classes\Factories;

use App\Models\SupplierAddress;

class SupplierAddressFactory extends ResourceFactoryAbstract{
    /**
     * Create and return an instance of App\Models\SupplierAddress model
     * @return SupplierAddress
     */
    public static function make(){
        return new SupplierAddress;
    }
        
}

