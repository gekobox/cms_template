<?php

namespace App\Models;

use App\Classes\FileManager;


class ProductImage extends AppModel
{
    public static $validationRules=[
        'product_id' => 'required|exists:products,id',
        'image' => 'required',        
    ];  
    
    // validation custom messages in the format 'attribute' => 'reference to translation value'
    public static $validationMessages=[
        'image.required' => 'models/ProductImage.image_validation',
    ];
    
    private static $uploadDir= '/uploads/product-images';

    public static function getList(Array $filterOptions, $fieldName='', $translateFile= null) {
        $data = [];
        if(isset($filterOptions['product'])){

         $items = static::where('product_id', $filterOptions['product'])
                     ->orderBy('seq')->get();

             foreach($items as $Item){
                 $data[] = [
                     'id' => $Item->id,
                     'name' => $Item->image_url
                 ];
             }
        }
         return response()->json($data);
    }
    
    public function deleteResource(){
        FileManager::deleteFile(public_path(static::$uploadDir), $this->image_name);
        parent::delete();
    }
}
