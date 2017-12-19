<?php
namespace App\Classes\Helpers\EcommercePlatforms\Magento;

class Order{    
    public static $resources=[       
        //API endpoints
        "cart_endpoint" => "/rest/V1/guest-carts/",     
        "order_endpoint" => "/rest/V1/order/",
        "all_orders_endpoint" => "/rest/V1/orders/",
    ];
 
    public static $platform= null;
    
    public static function registerOrder($Order, $client, $platform){
        //create a shopping cart into magento
        $cartId= static::createCart($client);
        static::$platform= $platform;
        //add items from orderlines to magento cart
        foreach($Order->orderLines as $OrderLine){
            static::addToCart($client, $OrderLine, $cartId);
        }
        
        //add shipping information
        static::addShippingInfo($client, $Order, $cartId);
        
        //place order
        $orderId= static::placeOrder($client, $cartId);
        
        //set the order as paid (Complete)
        static::completeOrder($client, $orderId);
        
        static::insertComment($client, $orderId);
    }
    
    /**
     * Create a shopping cart in magento
     * @param type $client
     * @return type
     */
    public static function createCart($client){
        $result = $client->post(static::$resources["cart_endpoint"])->send()->json();      
        return $result;
    }
    
    /**
     * Add the orderlines to the magento's shopping cart
     * @param type $client
     * @param type $OrderLine
     * @param type $quoteId
     * @return type
     */
    public static function addToCart($client, $OrderLine, $quoteId){
        $data["cartItem"]=[
            "quote_id" => $quoteId,
            "sku" => $OrderLine->ecommerce_code,
            "qty" => $OrderLine->amount
        ];
        
        $result = $client->post(static::$resources["cart_endpoint"]. $quoteId . "/items",[], json_encode($data))->send()->json();      
        return $result;
    }
    
    /**
     * Add shipping info to the cart in magento, this is going to be used as the order information later on
     * @param type $client
     * @param type $Order
     * @param type $quoteId
     * @return type
     */
    public static function addShippingInfo($client, $Order, $quoteId){
        $relation= MagentoHelper::getResource(static::$platform, "customer", $Order->ecommerce_relation_code);        
        
        $data["addressInformation"]=[
            "shipping_address" =>[
                "region" => $relation["addresses"][0]["region"]["region"],
                "region_id" => $relation["addresses"][0]["region_id"],
                "region_code" => $relation["addresses"][0]["region"]["region_code"],
                "country_id" => $relation["addresses"][0]["country_id"],
                "street"=>[
                    $relation["addresses"][0]["street"][0]
                ],
                "postcode" => $relation["addresses"][0]["postcode"],
                "city" => $relation["addresses"][0]["city"],
                "firstname" => $relation["firstname"],
                "lastname" => $relation["lastname"],
                "email" => $relation["email"],
                "telephone" => $relation["addresses"][0]["telephone"]
            ],
            "billing_address" =>[
                "region" => $relation["addresses"][0]["region"]["region"],
                "region_id" => $relation["addresses"][0]["region_id"],
                "region_code" => $relation["addresses"][0]["region"]["region_code"],
                "country_id" => $relation["addresses"][0]["country_id"],
                "street"=>[
                    $relation["addresses"][0]["street"][0]
                ],
                "postcode" => $relation["addresses"][0]["postcode"],
                "city" => $relation["addresses"][0]["city"],
                "firstname" => $relation["firstname"],
                "lastname" => $relation["lastname"],
                "email" => $relation["email"],
                "telephone" => $relation["addresses"][0]["telephone"]
            ],
            "shipping_carrier_code" => "flatrate",
            "shipping_method_code" => "flatrate"
        ];
        
        
        $result = $client->post(static::$resources["cart_endpoint"]. $quoteId . "/shipping-information",[], json_encode($data))->send()->json();      
        return $result;
    }
    
    /**
     * Create order
     * @param type $client
     * @param type $quoteId
     * @return type
     */
    public static function placeOrder($client, $quoteId){
        $data["paymentMethod"]=[
            "method" => "checkmo"
        ];
        
        $result = $client->put(static::$resources["cart_endpoint"]. $quoteId . "/order",[], json_encode($data))->send()->json();          
        return $result;
    }
    
    public static function insertComment($client, $orderId){
        $data["statusHistory"]=[
            "comment" => "Vendata order",
            "status" => "complete",
            "isVisibleOnFront" => 1
        ];        
        $result = $client->post(static::$resources["all_orders_endpoint"]. $orderId ."/comments",[], json_encode($data))->send()->json();
    }
    
    /**
     * For an order to be set as complete in magento it must be invoiced and shipped first
     * 
     * @param type $client
     * @param type $orderId
     * @return type
     */
    public static function completeOrder($client, $orderId){
        
        //Create the invoice for the order
        $data=[
            "capture" => true,
        ];        
        $result = $client->post(static::$resources["order_endpoint"]. $orderId ."/invoice",[], json_encode($data))->send()->json();          
        
        //Ship the order
        $data=[
            "items" => [],
        ];        
        $result = $client->post(static::$resources["order_endpoint"]. $orderId ."/ship",[], json_encode($data))->send()->json();          
        
        return $result;
    }
}