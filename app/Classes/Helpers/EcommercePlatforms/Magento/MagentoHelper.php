<?php

namespace App\Classes\Helpers\EcommercePlatforms\Magento;

use App\Models\EcommercePlatformSetting;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Illuminate\Support\Facades\Log;
use Magento\Client\Rest\MagentoRestClient;

/**
 * This class is used to connect to Magento's API and start communicating with it
 */
class MagentoHelper{
        
    private static $client=null;
    private static $attributes=[];
    public static $helperNamespace="\\App\\Classes\\Helpers\\EcommercePlatforms\\Magento\\";
    
    /**
     * Get or create the API's client supplying the corresponding credentials.
     * These credentials are obtained from the Interaction section of Magento's admin panel
     * 
     * @param type $platform
     * @return type
     */
    private static function createClient($platform){
        $settings= EcommercePlatformSetting::where("platform_id", $platform->id)->first();        
        
        static::$attributes["base_url"]= $settings->select("value")->where("attribute", "base_url")->first()->value;
        static::$attributes['consumer_key']=$settings->select("value")->where("attribute", "consumer_key")->first()->value;
        static::$attributes['consumer_secret'] = $settings->select("value")->where("attribute", "consumer_secret")->first()->value;
        static::$attributes['token'] = $settings->select("value")->where("attribute", "token")->first()->value;
        static::$attributes['token_secret']= $settings->select("value")->where("attribute", "token_secret")->first()->value;        
        
        if(static::$client == null){
            static::$client = MagentoRestClient::factory(array(
                'base_url'        => static::$attributes["base_url"],
                'consumer_key'    => static::$attributes['consumer_key'],
                'consumer_secret' => static::$attributes['consumer_secret'],
                'token'           => static::$attributes['token'],
                'token_secret'    => static::$attributes['token_secret']
            ));
        }
        
        return static::$client;
    }
    
    /**
     * Request for a list of a given resource type filtered a by search term
     * 
     * @param type $platform obtained from ecommerce_platform_settings table
     * @param type $searchTerm to filter the query
     * @param type $resourceType e.g= product
     * @param type $filters it can be a group of desired ids for example
     * @return type  Array 
     */
    public static function getResourceList($platform, $searchTerm, $resourceType,$searchField=null, $filters=[]){
        $startTime=time();
        $client= static::createClient($platform);
        $params= static::_buildSearchCriteria($searchTerm, $filters, $resourceType, $searchField);
        $helperClass= static::$helperNamespace . ucfirst($resourceType);
        $result = $client->get($helperClass::$resources["search_endpoint"] .'?'.$params)
                ->send()->json();
        $items=[];
        foreach($result["items"] as $item){
            $items[]= $helperClass::getFormattedItem($item, static::$attributes);            
        }
        return $items;
    }
    
    /**
     * build the serach criteria for the given resource
     * 
     * @param type $searchTerm
     * @param type $filters
     * @param type $resourceType
     * @return type
     */
    private static function _buildSearchCriteria($searchTerm, $filters, $resourceType, $searchField=null){
        $params="";        
        $helperClass= static::$helperNamespace . ucfirst($resourceType);
        $searchFields= $helperClass::$resources["searchFields"];
        $paramIndex=0;
        
        //build the search query with only the $searchField when it is defined
        //, otherwise use the seachFields defined in the resources array in the helper class
        if(isset($searchField) && count($filters) > 0){            
            $params = static::_searchCriteriaParams($paramIndex, $searchField, $searchTerm, $filters);            
        }
        else{
            foreach($searchFields as $_searchField){
                $params .= static::_searchCriteriaParams($paramIndex, $_searchField, $searchTerm, $filters);
            }
            
        }
        //remove last &
        $params= substr($params, 0, strlen($params)-1);            
        
        return $params;
    }
    
    /**
     * build the parameters for the search criteria using the filter values or not
     * @param type $filters
     * @return type
     */
    private static function _searchCriteriaParams(&$i, $searchField, $searchTerm=null, $filters=[]){
        $params="";        
        $searchTermFilter= isset($searchTerm) ? urlencode("%".$searchTerm."%") : "";        
        if(count($filters) > 0){            
            foreach($filters as $filter){
                $params .= "searchCriteria[filter_groups][0][filters][". $i ."][field]=". $searchField
                        . "&searchCriteria[filter_groups][0][filters][". $i ."][value]=".$filter
                        ."&searchCriteria[filter_groups][0][filters][". $i ."][condition_type]=eq&";
                $i++;
            }                
        }
        else{
            $params.= "searchCriteria[filter_groups][0][filters][". $i ."][field]=". $searchField
                    . "&searchCriteria[filter_groups][0][filters][". $i ."][value]=".$searchTermFilter
                    . "&searchCriteria[filter_groups][0][filters][". $i ."][condition_type]=like&";
            $i++;
        }
        $params= preg_replace("/\s+/", "", $params);
        return $params;
    }
    
    public static function createAddresses($telephone){
        return [
            "telephone" => $telephone,
            "firstname" =>"",
            "lastname" => "",
            "street" => [],
            "city" => "",
            "postcode"=>"",
            "countryId"=>""
        ];
    }
    
    /**
     * Save a resource in Magento
     * 
     * @param type $platform obtained from ecommerce_platform_settings table
     * @param type $resourceType
     * @param type $request
     * @return \App\Classes\Helpers\EcommercePlatforms\Magento\Guzzle\Http\Exception\ClientErrorResponseException
     */
    public static function saveResource($platform, $resourceType, $request){
        $result= "nothing saved";
        $helperClass= static::$helperNamespace . ucfirst($resourceType);
        
        $client= static::createClient($platform);

        $requestBody=null;
        foreach($helperClass::$resources["fields"] as $vendataField =>$magentoField){
            if($vendataField == "phone"){
                //$requestBody["customer"]["addresses"][0]= static::createAddresses($request[$vendataField]);
            }
            else{
                $requestBody["customer"][$magentoField]= $request[$vendataField];
            }
        }
        $requestBody["customer"]["websiteId"]=0;
        $requestBody= json_encode($requestBody);

        try{
            $result = $client->put($helperClass::$resources["endpoint"] . $request->code, []
                ,$requestBody)->send()->json();                        

        }catch(ClientErrorResponseException $e){
            return $e;
        }catch (BadResponseException $e){
            return $e->getRequest()->getResponse()->getBody(true);
        }
        
        return $result;
    }
    
    /**
     * Get a resource from Magento
     * @param type $platform obtained from ecommerce_platform_settings table
     * @param type $resourceType e.g: customer
     * @param type $resourceId identifier of the desired resource
     * @return type
     */
    public static function getResource($platform, $resourceType, $resourceId){
        $result= "not found";        
        $client= static::createClient($platform);            
        $helperClass= static::$helperNamespace . ucfirst($resourceType);
        $result = $client->get($helperClass::$resources["endpoint"] . $resourceId)->send()->json();      
        
        return $result;
    }
    
    /**
     * Register a paid order into Magento
     */
    public static function registerOrder($platform, $orderId){
        //get the order from vendata
        $Order= \App\Models\Order::find($orderId);
        if(isset($Order)){
            $client= static::createClient($platform);
            Order::registerOrder($Order, $client, $platform);
        }
    }
}
