<?php
namespace App\Classes\Factories;

use App\Models\Language;

class LanguageFactory extends ResourceFactoryAbstract{
    /**
     * Create and return an instance of App\Models\Language model
     * @return Language
     */
    public static function make(){
        return new Language;
    }
}