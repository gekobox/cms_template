<?php

namespace App\Models;

use App\Classes\Interfaces\IResource;

class Attribute extends AppModel
{       
    //Validation
    public static $validationRules=[
        'data_type_id' => 'required|exists:data_types,id',
        'name' => 'required',        
    ];  
    
    // validation custom messages in the format 'attribute' => 'reference to translation value'
    public static $validationMessages=[
        'name.required' => 'models/Attribute.name_validation',
    ];
    
    public function dataType(){
        return $this->belongsTo('App\Models\DataType');
    }
        
    public static function dropdownOptions($valueField = 'id', $textField = 'name') {
        $records= static::all();
        
        $data=[
            [
                'value' => '',
                'text' => '',
                'code' => ''
                
            ]];
        foreach($records as $Record){
            $data[]= [
                'value' => $Record->$valueField,
                'text' => $Record->$textField,
                'code' => $Record->dataType->code
            ];
        }
        
        return $data;
    }   
}
