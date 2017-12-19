<?php
namespace App\Classes\Helpers\EcommercePlatforms\Magento;

class Product{
    private static $magentoMediaPath="/pub/media/catalog/product";
    public static $resources=[       
        //API endpoint to search a resource
        "search_endpoint" => "/rest/V1/products",
        // used to create the search criteria
        "searchFields" =>[            
            "sku", "name"            
        ]        
    ];
    
    /**
     * format the result that comes from an API request in order to show on Vendata's frontend
     * @param type $item
     * @param type $attributes
     * @return type
     */
    public static function getFormattedItem($item, $attributes=null){
        
        $formattedItem=[
            "name" => $item["name"],
            "sku" => $item["sku"],
            "image"=> $attributes["base_url"] . static::$magentoMediaPath. static::getImageAttribute($item),
            "price" => isset($item["price"]) ? $item["price"] : ""
        ];
        
        return $formattedItem;
    }
    
    /**
     * get the image name from within the returned Product's array
     * 
     * @param type $Product
     * @return type
     */
    private static function getImageAttribute($Product){
        $image="";
        foreach($Product["custom_attributes"] as $attribute){
            if($attribute["attribute_code"] == "image"){
                $image= $attribute["value"];
                break;
            }
        }
        return $image;
    }
}