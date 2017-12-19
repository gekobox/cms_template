<?php

namespace App\Models;

use App\Classes\Interfaces\IResource;

class Country extends AppModel
{       
    /* relations */
    public function countryTranslation(){
        return $this->hasMany('App\Models\CountryTranslation');
    }
    
    public $translationMethod = 'countryTranslation';
    
    /* application */
    public static function dropdownOptions($valueField = 'id', $textField = 'name') {
        $records= static::all();
        
        $data=[
            [
                'value' => '',
                'text' => '',
                
            ]];
        foreach($records as $Record){
            $data[]= [
                'value' => $Record->$valueField,
                'text' => $Record->translation()->$textField,                
            ];
        }
        
        return $data;
    }   
}
