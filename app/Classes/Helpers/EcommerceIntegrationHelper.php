<?php

namespace App\Classes\Helpers;

use App\Classes\Helpers\EcommercePlatforms\Magento\MagentoHelper;
use App\Models\EcommercePlatform;
use Illuminate\Http\Request;

/**
 * This class serves as a gateway through which all communication with any 
 * ecommerce platform goes, it has all methods required to talk with the POS section on Vendata
 * To connect to the ecommerce api's it uses the helpers defined in Classes\Helpers\EcommercePlatform dir,
 * inside this folder each platform need a root folder (eg. Magento) and inside the methods corresponding
 * to each resource and a main helper which communicates with the corresponding APIs
 * 
 */
class EcommerceIntegrationHelper{
       
    /**
     * Contains all the currently available ecommerce platforms with their corresponding helpers
     * it is composed by the [platform code] => [namespace of helper's classes]
     * @var type array
     */
    private static $availablePlatforms=[
        "magento" => "\\App\\Classes\\Helpers\\EcommercePlatforms\\Magento\\"
    ];
    
    /**
     * Check whether vendata has an active ecommerce platform. This is defined in the 
     * ecommerce_platform tables
     * 
     * @return boolean
     */
    public static function hasActivePlatform(){
        $platform= static::getActivePlatform();
        if(isset($platform)){
            return true;
        }
        return false;
    }
    
    /**
     * Get the currently active platform, it case the database has multiple active platforms
     * it gets the first one
     * @return type
     */
    public static function getActivePlatform(){
        return EcommercePlatform::where('active',true)->first();
    }
    
    /**
     * Get the namespace corresponding to the active platform
     * @return type
     */
    public static function getActivePlatformNamespace(){
        $platform= static::getActivePlatform();
        $namespace="";
        $helperClass= static::$availablePlatforms[$platform->platform_code] 
                . ucfirst($platform->platform_code) . "Helper";
        $namespace= $helperClass::$helperNamespace;                
        return $namespace;
    }
    
    /**
     * Get a list of the given resource type from the ecommerce platform
     * 
     * @param type $searchTerm a text to filter the query
     * @param type $resourceType the type of the ecommerce resource. eg= product
     * @return type Array list of the filtered resources
     */
    public static function getResourceList($searchTerm = "", $resourceType ){
        $platform= static::getActivePlatform();
        $items=[];
        $helperClass= static::$availablePlatforms[$platform->platform_code] 
                . ucfirst($platform->platform_code) . "Helper";
        
        $items= $helperClass::getResourceList($platform, $searchTerm, $resourceType);
                        
        return $items;
    }
    
    /**
     * Get the products from the ecommerce platform associated to
     * a given order in Vendata
     * 
     * @param type $Order reference to the order associated to the products
     */    
    public static function getOrderProducts(&$Order){
        if(isset($Order)){
            $productCodes=[];
            $platform= static::getActivePlatform();

            foreach($Order->orderLines as $OrderLine){
                $productCodes[]= $OrderLine->ecommerce_code;
            }
            $helperClass= static::$availablePlatforms[$platform->platform_code] 
                    . ucfirst($platform->platform_code) . "Helper";

            $productList= $helperClass::getResourceList($platform, "", "product", "sku" ,$productCodes);

            //associate the magento products with the order lines
            foreach($Order->orderLines as $OrderLine){
                foreach($productList as $product){
                    if($OrderLine->ecommerce_code == $product["sku"]){
                        $OrderLine->product= $product;
                    }
                }
            }
        }
    }
    
    public static function registerPaidOrder($orderId){
        $platform= static::getActivePlatform();
        $helperClass= static::$availablePlatforms[$platform->platform_code] 
                    . ucfirst($platform->platform_code) . "Helper";
        $helperClass::registerOrder($platform, $orderId);
    }
    /**
     * Save a given resource to the ecommerce platform using the API of the currently active platform
     * 
     * @param Request $request
     * @param type $resourceType
     * @return type
     */
    public static function saveResource(Request $request, $resourceType){
        $platform= static::getActivePlatform();
        $result="nothing saved";
        $helperClass= static::$availablePlatforms[$platform->platform_code] 
                . ucfirst($platform->platform_code) . "Helper";
        
        $result= $helperClass::saveResource($platform, $resourceType, $request);
                
        return $result;
    }
    
    /**
     * Get a resource from the ecommerce platform using the API
     * 
     * @param type $resourceType the type of the resource. e.g.= customer
     * @param type $resourceId resource's identifier as defined in the ecommerce platform     * 
     * @return type Resource
     */
    public static function getResource($resourceType, $resourceId){
        $platform= static::getActivePlatform();
        $result="resource not found";
        $helperClass= static::$availablePlatforms[$platform->platform_code] 
                . ucfirst($platform->platform_code) . "Helper";
        
        $result= $helperClass::getResource($platform, $resourceType, $resourceId);
        
        return $result;
    }
}
