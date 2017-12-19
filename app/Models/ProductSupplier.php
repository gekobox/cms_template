<?php

namespace App\Models;

use App\Classes\Interfaces\IResource;

class ProductSupplier extends AppModel
{       
    protected $table='product_supplier';
    
    //Validation
    public static $validationRules=[
        'product_id' => 'required|exists:products,id',
        'supplier_id' => 'required|exists:suppliers,id',        
    ];  
    
    //Relations
    public function supplier(){
        return $this->hasOne('App\Models\Supplier','id','supplier_id');
    }
    
    //Application
    
    public static function getList(Array $filterOptions, $fieldName='', $translationFile=null) {
       $data = [];
       if(isset($filterOptions['product'])){
        
        $items = static::where('product_id', $filterOptions['product'])->get();
        
            foreach($items as $Item){
                $data[] = [
                    'id' => $Item->id,
                    trans('models/ProductSupplier.name_column.name') => $Item->supplier->name
                ];
            }
       }
        return response()->json($data);
    }
    
    /*public function save($data, $saveTranslation = true) {
        $this->product_id= \Illuminate\Support\Facades\Request::get('product_id');
        parent::save($data, $saveTranslation);
    }*/
}
