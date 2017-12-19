<?php

namespace App\Models;

use App\Classes\Helpers\EcommerceIntegrationHelper;

class Order extends AppModel
{       
    const OPEN= 'open';
    const PARKED= 'parked';
    const CLOSED= 'closed';
    
    public function orderLines(){
        return $this->hasMany('\App\Models\OrderLine');
    }
            
    /**
     * Override the getList interface implemantation
     * @param array $filterOptions
     * @param type $displayField
     * @return type
     */
    public static function getList(Array $filterOptions, $displayField= 'name', $translationFile=null) {
       $data = [];
       //define the order status to filter
       $status= static::OPEN;
       if(isset($filterOptions['order_status'])){
           $status= $filterOptions['order_status'];
       }
        $items = static::where('status', $status)->get();
        foreach($items as $Item){
            $name="";
            
            //get the customer name when it is set for the current order            
            if($Item->ecommerce_relation_code != null && $Item->ecommerce_relation_code != ''){
                $relation= EcommerceIntegrationHelper::getResource("customer", $Item->ecommerce_relation_code);            

                if(isset($relation['firstname']) && isset($relation['lastname'])){
                    $name= $relation["firstname"]. " ". $relation["lastname"];
                }
            }
            
            //when status is closed get also the total price
            if($status == static::CLOSED){
                $data[] = [
                    'id' => $Item->id,
                    'name' => $name,
                    'date' => $Item->created_at->toDateTimeString(),
                    'total price' => $Item->total()
                ];
            }
            else{
                $data[] = [
                    'id' => $Item->id,
                    'name' => $name,
                    'date' => $Item->created_at->toDateTimeString(),                        
                ];
            }


        }
        return response()->json($data);
    }
    
    /**
     * calculate the total price of the order
     */
    public function total(){
        $total=0;
        // get the price of each order line
        foreach($this->orderLines as $OrderLine){
            $total += $OrderLine->price;
        }
        return $total;
    }
}
