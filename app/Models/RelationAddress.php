<?php

namespace App\Models;

use App\Classes\Interfaces\IResource;

class RelationAddress extends AppModel
{       
    //Validation
    public static $validationRules=[
        'relation_id' => 'required|exists:relations,id',
        'address_type_id' => 'required|exists:address_types,id',        
        'country_id' => 'required|exists:countries,id'
    ]; 
    
    public static function getList(Array $filterOptions, $displayField= 'street', $translationFile= null) {
       return parent::getList($filterOptions, $displayField, $translationFile);
    }
}
