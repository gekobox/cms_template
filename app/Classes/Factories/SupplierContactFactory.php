<?php
namespace App\Classes\Factories;

use App\Models\SupplierContact;

class SupplierContactFactory extends ResourceFactoryAbstract{
    /**
     * Create and return an instance of App\Models\SupplierContact model
     * @return SupplierContact
     */
    public static function make(){
        return new SupplierContact;
    }
        
}

