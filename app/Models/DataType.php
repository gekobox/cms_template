<?php

namespace App\Models;

use App\Classes\Interfaces\IResource;

class DataType extends AppModel
{   
    public function dataTypeTranslation(){
        return $this->hasMany('App\Models\DataTypeTranslation');
    }
    
    public $translationMethod = 'dataTypeTranslation';
    /* Class methods */
    public static function getList(Array $filterOptions, $displayField = 'name', $translationFile=null) {
       $data = [];     
       $items = static::all();

        foreach($items as $Item){
            $data[] = [
                'id' => $Item->id,
                'code' => $Item->code
            ];
        }       
        
        return response()->json($data);
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
                'text' => $Record->translation()->$textField,
                'code' => $Record->code
            ];
        }
        
        return $data;
    }   
    
}
