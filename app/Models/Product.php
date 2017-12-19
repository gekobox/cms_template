<?php

namespace App\Models;

use App\Classes\Interfaces\IResource;

class Product extends AppModel
{       
    public static $validationRules=[
        'name' => 'required',
        'sku' => 'required',                
    ];
    //Relations
    public function suppliers(){
        return $this->belongsToMany('App\Models\Supplier');
    }
    
    public function productImages(){
        return $this->hasMany('App\Models\ProductImage');
    }
    
    //Application
    
    /**
     * Override the getList interface implemantation
     * @param array $filterOptions
     * @param type $displayField
     * @return type
     */
    public static function getList(Array $filterOptions, $displayField= 'name', $translationFile=null) {
       $data = [];
        $items = static::all();
        foreach($items as $Item){
            $name= $Item->name;
            $image= isset($Item->productImages->first()->image_url) ? 
                        $Item->productImages->first()->image_url : '';
            $data[] = [
                'id' => $Item->id,
                trans('models/Product.name_column.name') => $name,
                'image' => $image,
            ];
        }
        return response()->json($data);
    }
}
