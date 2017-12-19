<?php
/**
 * ResourceManager is used to manage all resources (models and models with translation) 
 * so that every request for any models must pass through this class
 */
namespace App\Classes;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class ResourceManager{
      
    public static $factoryPrefix= 'App\\Classes\\Factories\\';
    public static $modelPrefix= 'App\\Models\\';
   
    /**
     * Calls the factory of the given resource type to get an instance of that resource
     * @param string $resourceType the resource type passed through the http request 
     *                      in camel case format or the regular model's name
     * 
     * @return object instance of the given resource type 
     * @throws \Exception
     */
    public static function make($resourceType){
        static::handleDBConnection($resourceType);
        $resourceType= ucfirst($resourceType);
        $className= static::$factoryPrefix . $resourceType . "Factory";
        if(class_exists($className)){
            return $className::make();
        }
        else{
            throw new \Exception("The class ". $className. " is not valid");
        }
        
    }
    
    /**
     * Get a list of the given resource type with optional filter options
     * 
     * @param string $resourceType resource type name in camel case or the regular model's name
     * @param array $filterOption
     * @return array list list of items of the given resource type
     */
    public static function getList($resourceType, Array $filterOption = []){
        static::handleDBConnection($resourceType);
        $resourceClass= static::getResourceClass($resourceType);
        return $resourceClass::getList($filterOption);
    }
    
    /**
     * Get a specific instance of a given resource type
     * 
     * @param string $resourceType resource type name in camel case or the regular model's name
     * @param type $resourceId id of the wanted resource instance
     * @return object instance of the given resource type
     */
    public static function get($resourceType, $resourceId){
        static::handleDBConnection($resourceType);
        $resourceClass= static::getResourceClass($resourceType);
        return $resourceClass::get($resourceId);        
    }
    
    /**
     * Delete instances of the given resource type
     * 
     * @param string $resourceType resource type name in camel case or the regular model's name
     * @param array $resourceIds list of ids of the items to delete
     * @return int number of deleted instances
     */
    public static function delete($resourceType, $resourceIds){
        static::handleDBConnection($resourceType);
        $resourceClass= static::getResourceClass($resourceType);

        $Resources= $resourceClass::findMany($resourceIds);
        $deletedResources=0;
        foreach($Resources as $Resource){
            $Resource->deleteResource();
            $deletedResources ++;
        }
        return $deletedResources;

    }
    
    /**
     * Get a list of the given resource type to be used on a dropdown element
     * 
     * @param string $resourceType resource type name in camel case or the regular model's name
     * @return array list of items prepared to be used in <option></option> tags of <select> elements
     */
    public static function getDropdownOptions($resourceType){
        static::handleDBConnection($resourceType);
        $resourceClass= static::getResourceClass($resourceType);
        $records= $resourceClass::dropdownOptions();
        return $records;
    }
    
    /**
     * Get the validation rules array from the model corresponding to the given resource type
     * @param string $resourceType resource type name in camel case or the regular model's name
     * @return array validation rules array
     */
    public static function getValidationRules($resourceType){
        $resourceClass= static::getResourceClass($resourceType);
        $records= $resourceClass::$validationRules;
        return $records;
    }
    
    /**
     * Get the custom messages for the validation rules
     * @param string $resourceType resource type name in camel case or the regular model's name
     * @return array validation rules array
     */
    public static function getValidationMessages($resourceType){
        $resourceClass= static::getResourceClass($resourceType);
        $records=[];
        
        //check whether the resource has custom validation messages
        if(isset($resourceClass::$validationMessages)){
            $records= $resourceClass::$validationMessages;
        }
        if (isset($records)){
            //translate each attribute message
            foreach($records as $key => $translationVal){                
                $records[$key]= trans($translationVal);
            }
        }
        return $records;
    }
    
    /**
     * Get the resource FQDN 
     * @param string $resourceType resource type name in camel case or the regular model's name
     * @return FQDN of the resource model
     * @throws \Exception
     */
    public static function getResourceClass($resourceType){
        $resourceType= ucfirst($resourceType);
        $className= static::$modelPrefix . $resourceType;
        
        if(class_exists($className)){            
            return $className;
        }
        else{
            throw new \Exception("The class ". $className. " is not valid");
        }
    }
    
    /**
     * Switch the db connection to the shared db when the resource is a common 
     * resource, for example Account resource
     * @param type $resourceType
     */
    private static function handleDBConnection($resourceType){
        
        //switch to shared db when resource is Account
        if($resourceType == 'account'){
            Config::set('database.default', 'mysql');
            \DB::purge('mysql');
            \DB::reconnect('mysql');
        }
    }
    /**
     * Check the user's permission on the given resource
     * @param type $action
     * @param type $resourceType
     */
    public static function authorize($action, $resourceType){
        
        $resourceClass= static::getResourceClass($resourceType);
        $Controller= new \App\Http\Controllers\Controller();
        $Controller->authorize($action, $resourceClass);        
    }
    
    /**
     * Check the user's permission on the given resource available on the ecommerce platform
     * @param type $action
     * @param type $resourceType
     */
    public static function authorizeEcommerce($action, $resourceType){
        
        $activePlatformNamespace= Helpers\EcommerceIntegrationHelper::getActivePlatformNamespace();
        $resourceClass= $activePlatformNamespace . ucfirst($resourceType);
        $policyClass= "\\App\\Policies\\".ucfirst($resourceType)."Policy";
        
        $policy= new $policyClass();
        return $policy->read(\Illuminate\Support\Facades\Auth::user());
    }
}
