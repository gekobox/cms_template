<?php

namespace App\Models;

use App\Classes\Interfaces\IResource;

class SupplierContact extends AppModel
{       
    //Validation
    public static $validationRules=[
        'supplier_id' => 'required|exists:suppliers,id',
    ]; 
    
    public static function getList(Array $filterOptions, $displayField= 'email', $translationFile=null) {
       return parent::getList($filterOptions, $displayField, $translationFile);
    }
}
