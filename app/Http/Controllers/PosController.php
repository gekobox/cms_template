<?php

namespace App\Http\Controllers;

use App\Classes\Helpers\EcommerceIntegrationHelper;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Product;
use App\Models\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PosController extends Controller
{
    /**
     * Get the list of relations to display on the relation search bar on the POS section
     * Compare the relation's name, and email with the given search term
     * 
     * @param Request $request
     * @return type
     */
    public function getRelations(Request $request){
        $relations=[];
        if($request->has('searchTerm') && $request->searchTerm !== ''){
            $searchTerm= trim($request->searchTerm);
            
            if(EcommerceIntegrationHelper::hasActivePlatform()){
                $relations= EcommerceIntegrationHelper::getResourceList($searchTerm, "customer");
            }
            else{
                $relations= Relation::select(
                            'relations.id as id',
                            'is_customer',
                            'is_business',
                            'relations.name as name',
                            'notes',
                            'first_name',
                            'last_name',
                            'phone',
                            'email'
                        )
                        ->where('name','like',"%" .$searchTerm ."%")
                        ->orWhere('relation_contacts.email', 'like',"%" .$searchTerm ."%")
                        ->leftJoin('relation_contacts', 'relation_contacts.relation_id', '=', 'relations.id')
                        ->get();
            }
        }
        
        return $relations;
    }
    
    /**
     * Load the order's currently selected relation, this is used to show 
     * the relation card in the POS section above the order lines
     * 
     * @param Request $request
     * @return type
     */
    public function loadRelation(Request $request){
        $Relation=null;
        if($request->has('relation_code') && $request->relation_code != 'null'){
            if(EcommerceIntegrationHelper::hasActivePlatform()){                
                $Relation= EcommerceIntegrationHelper::getResource("customer", $request->relation_code);                
            }
            
        }
        
        return $Relation;
    }
    
    /**
     * Add the selected relation to the current order
     * 
     * @param Request $request
     * @return type
     */
    public function addRelationToOrder(Request $request){
        $this->validate($request, [            
            'relation_code' => 'required'
        ]);
        
        // create the order if it doesn't exist yet
        $Order= Order::find($request->order);            
        if(!isset($Order)){
            $Order= $this->addOrder();
        }
            
        if(isset($Order)){            
            //add ecommerce customer code when relation_code is supplied in the request            
            $Order->ecommerce_relation_code= $request->relation_code;
            
            $Order->save();
        }
        
        //return the id of the order to which the given relation was assigned
        
        return ['order' => $Order->id];
    }
    
    /**
     * Remove the relation form the current order by setting it to null
     * 
     * @param Request $request
     * @return type
     */
    public function removeRelationFromOrder(Request $request){
        $this->validate($request, [
            'order' => 'required'
        ]);
        
        $Order= Order::find($request->order);
        if(isset($Order)){
            $Order->ecommerce_relation_code= null;
            $Order->save();
        }
        
        return $Order;
    }
    
    /**
     * Get the products to be displayed on the product's grid on the POS section.
     * It filters the products by matching the name, sku, or ean with the given search term
     * 
     * @param Request $request
     * @return type
     */
    public function getProductList(Request $request){
        $products=[];
        if($request->has('searchTerm') && $request->searchTerm !== ''){
            $searchTerm= trim($request->searchTerm);
            
            if(EcommerceIntegrationHelper::hasActivePlatform()){
                $products= EcommerceIntegrationHelper::getResourceList($searchTerm, "product");
            }
            else{
                $products= Product::where('name','like',"%" .$searchTerm ."%")
                            ->orWhere('sku','like',"%" .$searchTerm ."%")
                            ->orWhere('ean','like',"%" .$searchTerm ."%")
                            ->get();

                // add images to the filtered list of products
                $this->_addImagesToProducts($products);
            }
        }       
        
        return $products;
    }
    
    /**
     * Add the first product image url to each element on the products array
     * @param type $products
     */
    private function _addImagesToProducts(&$products){
        foreach($products as $Product){
            $Image= $Product->productImages->first();
            $Product->image= isset($Image) ? $Image->image_url : '';
        }
    }
    
    /**
     * Load the currenlty open order along with the corresponding order lines
     * and format the data to be used in the order lines card in the POS section
     * @param Request $request
     * @return type
     */
    public function loadOrder(Request $request){Log::info("loading order");
        $orderLines=[];
        $Order= null;
        $startTime=time();
        // when an order id was supplied, get that order otherwise get the 
        // first open order   
        if($request->has('order') && $request->order != 'null'){
            if(EcommerceIntegrationHelper::hasActivePlatform()){
                $Order= Order::with('orderLines')->find($request->order);                  
                EcommerceIntegrationHelper::getOrderProducts($Order);
            }
            else{
                $Order= Order::with('orderLines', 'orderLines.product')->find($request->order);            
            }
        }
        else{
            if(EcommerceIntegrationHelper::hasActivePlatform()){
                $Order= Order::where('status', Order::OPEN)->with('orderLines')->first();   
                //only call the API when the order has orderlines
                if(count($Order->orderLines) > 0){
                    EcommerceIntegrationHelper::getOrderProducts($Order);
                }
            }
            else{
                $Order= Order::where('status', Order::OPEN)->with('orderLines', 'orderLines.product')->first();            
            }
        }
        
        if(isset($Order)){            
            return $Order;
        }
        
        return response('not found', 404);
    }
    
    /**
     * Add an item to the order
     * 
     * @param Request $request
     * @return type
     */
    public function addOrderLine(Request $request){
        $this->validate($request, [
            'amount' => 'required',            
        ]);
        
        // create a new order when no order was found
        $Order= Order::where('id', $request->orderId)->first();        
        if(!isset($Order)){
            $Order= $this->addOrder();            
        }
        
        $OrderLine= new OrderLine();
        $OrderLine->order_id= $Order->id;
        $OrderLine->amount= $request->amount;
        $OrderLine->price= $request->price;
        $OrderLine->note= $request->note;
        //when the product is comming from an ecommerce platform save the product_code
        //from the request parameter
        if($request->has("product_code")){
            $OrderLine->ecommerce_code= $request->product_code;
        }
        
        $OrderLine->save();                
        
        return [
            'order' => $Order->id
        ];
    }
    
    public function updateOrderLine(Request $request){
        $this->validate($request, [
            'orderline' => 'required',
            'field' => 'required|in:amount,price,note',
            'value' => 'required'
        ]);
        
        $OrderLine= OrderLine::where('id', $request->orderline)->first();
        if(isset($OrderLine)){
            
            $OrderLine->{$request->field}= $request->value;
            $OrderLine->save();
        }
        
        return ['status' => 'ok'];
    }
    
    /**
     * Remove the given order line
     * @param type $orderLineId
     * @return string
     */
    public function removeOrderLine($orderLineId){
        $orderLine= OrderLine::find($orderLineId);
        if(isset($orderLine)){
            $orderLine->delete();
            return "ok";
        }
        
        return response('not found', 422);
    }
    
    /**
     * Create and return a new Order
     * @return Order
     */
    public function addOrder(){
        $Order= new Order();
        $Order->user_id= Auth::user()->id;
        $Order->save();
        return $Order;
    }
    
    /**
     * remove the given order
     * @param Request $request
     * @return string
     */
    public function discardOrder(Request $request){
        $this->validate($request, ['order' => 'required']);
        
        $Order= Order::find($request->order);
        if(isset($Order)){
            $Order->delete();
            return 'ok';
        }
        
        return response('not found', 404);
    }
    
    /**
     * set the status of the given order as 'parked'
     * @param Request $request
     * @return string
     */
    public function parkOrder(Request $request){
        $this->validate($request, ['order' => 'required']);
        
        $Order= Order::find($request->order);
        if(isset($Order)){
            $Order->status= Order::PARKED;
            $Order->save();
            return 'ok';
        }
        
        return response('not found', 404);
    }
    
    /**
     * change the status of the given order to "OPEN"
     * @param type $orderId
     * @return string
     */
    public function reopenOrder($orderId){
        $Order= Order::find($orderId);
        if(isset($Order)){
            $Order->status= Order::OPEN;
            $Order->save();
        }
        return 'ok';
    }
}
