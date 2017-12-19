<?php
namespace App\Classes\Factories;

use App\Models\AddressType;

class AddressTypeFactory extends ResourceFactoryAbstract{
   /**
     * Create and return an instance of App\Models\AddressType model
     * @return AddressType
     */
    public static function make(){
        return new AddressType;
    }
        
}

