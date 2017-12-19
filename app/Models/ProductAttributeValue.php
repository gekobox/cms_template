<?php

namespace App\Models;

use App\Classes\FileManager;


class ProductAttributeValue extends AppModel
{
   public static $validationRules=[
    'product_id' => 'required|exists:products,id',
    'attribute_id' => 'required|exists:attributes,id',
   ];  

   public function attribute(){
       return $this->belongsTo('App\Models\Attribute');
   }
   
   public static function getList(Array $filterOptions, $displayField= 'name', $translationFile=null) {
       $data = [];
       if(isset($filterOptions['product'])){
        
        $items = static::where('product_id', $filterOptions['product'])->get();
        
            foreach($items as $Item){
                $data[] = [
                    'id' => $Item->id,
                    trans('models/ProductAttributeValue.name_column.name') => $Item->attribute->name
                ];
            }
       }
        return response()->json($data);
    }
    
    public static function get($id) {
        $AttributeValue= parent::get($id);
        
        //get the attribute data type's code
        $AttributeValue->attribute_data_type= $AttributeValue->attribute->dataType->code;
        return $AttributeValue;
    }
}
