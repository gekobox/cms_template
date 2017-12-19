<?php

namespace App\Models;

use App\Classes\Interfaces\IResource;

class Supplier extends AppModel
{       
    public static $validationRules=[
        'name'=>'required'
    ];
    
    //Relations
    public function products(){
        return $this->belongsToMany('App\Models\Product');
    }
    
    public static function getList(Array $filterOptions, $displayField= 'name', $translationFile = 'Supplier') {
        return parent::getList($filterOptions, $displayField, $translationFile);
    }
}
