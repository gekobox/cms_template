<?php
namespace App\Classes\Factories;

use App\Models\Country;

class CountryFactory extends ResourceFactoryAbstract{
    /**
     * Create and return an instance of App\Models\Country model
     * @return Country
     */
    public static function make(){
        return new Country;
    }
}