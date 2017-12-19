<?php

namespace App\Models;

use App\Classes\Interfaces\IResource;

class RelationContact extends AppModel
{       
    //Validation
    public static $validationRules=[
        'relation_id' => 'required|exists:relations,id',        
    ]; 
    
    public static function getList(Array $filterOptions, $displayField= 'email', $translationFile=null) {
       return parent::getList($filterOptions, $displayField, $translationFile);
    }
}
