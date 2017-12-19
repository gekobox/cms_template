<?php

namespace App\Models;

use App\Classes\Interfaces\IResource;

class OrderHistory extends AppModel
{       
    /**
     * Override the getList interface implemantation
     * @param array $filterOptions
     * @param type $displayField
     * @return type
     */
    public static function getList(Array $filterOptions, $displayField= 'name', $translationFile=null) {
       $data = [];
        $items = static::where("ecommerce_relation_code", $filterOptions['relation_code'])->get();
        foreach($items as $Item){
            $data[] = [
                'id' => $Item->id,
                'Date' => $Item->order_date,
                'Total' => $Item->total,
            ];
        }
        return response()->json($data);
    }
}
