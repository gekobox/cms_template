<?php
namespace App\Classes\Factories;

use App\Models\RelationAddress;

class RelationAddressFactory extends ResourceFactoryAbstract{
    /**
     * Create and return an instance of App\Models\RelationAddress model
     * @return RelationAddress
     */
    public static function make(){
        return new RelationAddress();
    }
    
}

