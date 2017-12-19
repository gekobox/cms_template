<?php

namespace App\Http\Controllers;

use App\Classes\Helpers\EcommerceIntegrationHelper;
use App\Classes\ResourceManager;
use Illuminate\Http\Request;

class ResourcesController extends Controller
{
    
    public function getResource($resourceType, $resourceId){
        return ResourceManager::get($resourceType, $resourceId);
    }
    
    public function getEcommerceResource($resourceType, $resourceId){
        return EcommerceIntegrationHelper::getResource($resourceType, $resourceId);
    }
    /**
     * Get the list of the given resource type
     * @param type $resourceType
     * @return array
     */
    public function getResourceList($resourceType, Request $request){
        $resourceList= ResourceManager::getList($resourceType, $request->all());
        return $resourceList;
    }
    
    /**
     * Create a new resource or modify an existing one of the given $resourceType
     * @param Request $request
     * @param string $resourceType
     * @return json
     */
        
    public function saveResource(Request $request, $resourceType, $returnResource=false){
        $data= $request->all();       
        $result= 'error';
        //Validate request inputs
        $this->validate($request, ResourceManager::getValidationRules($resourceType)
                , ResourceManager::getValidationMessages($resourceType));
        
        if(isset($data['id'])){
            $Resource= ResourceManager::get($resourceType, $data['id']);
        }
        else{
            $Resource= ResourceManager::make($resourceType);                        
        }
        
        $result= $Resource->saveResource($data, true, $returnResource);
        
        if($returnResource){
            return $result;
        }
        
        return ["saved"=>$result];
    }
    
    /**
     * Save a compound resource, this is a resource with some related resources as children
     * The resource along with the children must be passed as a json string in the request with the following format
     *          data= {
     *              fields: {field1:'', field2:'', ..., fieldN:''},
     *              children: {
     *                  childResource1: {field1:'', field2:'', ..., fieldn:''},
     *                  childResource2: {field1:'', field2:'', ..., fieldn:''},
     *                  childResourceN: {field1:'', field2:'', ..., fieldn:''},
     *              }
     *          }
     * @param Request $request
     * @param type $resourceType
     */
    public function saveCompoundResource(Request $request, $resourceType){
        //get and decode the json string with the resource and its children
        $CompoundResource= json_decode($request->data, true);
        
        if(isset($CompoundResource['fields']) && isset($CompoundResource['children'])){
            //modify the request variable with the fields of the main resource
            //, this is used by the validator when saving the resource
            $request->replace($CompoundResource['fields']);
            
            //save and get the new resource
            $Resource= $this->saveResource($request, $resourceType, true);
            
            //save the children resources
            foreach($CompoundResource['children'] as $ChildResourceType => $ChildValues){
                //get the first child when the parent resource already has a child of this child resource type
                $existingChild= isset($Resource->{$ChildResourceType.'s'}) ? $Resource->{$ChildResourceType.'s'}->first() : null;
                
                //add the 'id' field to the child's values array so that 
                //the child is edited instead of created when it already exists
                if(isset($existingChild)) $ChildValues['id'] = $existingChild->id;
                
                //add the parent resource reference to the child's valus array
                $ChildValues[$resourceType . "_id"]= $Resource->id;
                
                //modify the request variable with the child's fields
                //, this is used by the validator when saving
                $request->replace($ChildValues);
                $this->saveResource($request, $ChildResourceType);                
            }
        }
    }
    
    public function saveEcommerceResource(Request $request, $resourceType){
        return EcommerceIntegrationHelper::saveResource($request, $resourceType);
    }
    /**
     * delete a resource of the given type
     * @param Request $request
     * @param type $resourceType
     * @return type
     */
    public function deleteResource(Request $request, $resourceType){
        $resourceIds= $request->input('ids');
        if(isset($resourceIds)){
            return ResourceManager::delete($resourceType, $resourceIds);
        }        
    }
    
    /**
     * get resource list for dropdown elements
     */
    public function getDropdownOptions($resourceType){
        $data= ResourceManager::getDropdownOptions($resourceType);        
        
        return response()->json($data);
    }
    
    /**
     * Sort the items in the given list by the seq field
     * @param Request $request
     */
    public function sortList(Request $request, $resourceType){
        //get the resource class
        $class= ResourceManager::getResourceClass($resourceType);
        
        $itemList= $request->items;

        //loop through the itemList and set the corresponding resource seq value
        $currentSeq=1;
        foreach($itemList as $itemId){
           $Resource= $class::find($itemId);
           $Resource->seq= $currentSeq;
           $Resource->save();
           $currentSeq ++;
        }
            
        return ['status'=>'ok'];
    }
}
