<?php
namespace App\Classes\Factories;

use App\Models\RelationContact;

class RelationContactFactory extends ResourceFactoryAbstract{
    /**
     * Create and return an instance of App\Models\RelationContact model
     * @return RelationContact
     */
    public static function make(){
        return new RelationContact();
    }
    
}

