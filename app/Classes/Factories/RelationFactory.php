<?php
namespace App\Classes\Factories;

use App\Models\Relation;

class RelationFactory extends ResourceFactoryAbstract{
    /**
     * Create and return an instance of App\Models\Relation model
     * @return Relation
     */
    public static function make(){
        return new Relation();
    }
    
}

