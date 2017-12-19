<?php

namespace App\Models;

use App\Classes\Interfaces\IResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class AppModel extends Model implements IResource
{     
    public static $validationRules=[];
    
    /**
     * fields associated to a resource which require translations
     * @var array
     */    
    protected $translated_fields=[];
    
    /**
     * Name of the resource (which normally corresponds to a db table). This name
     * is used for translation purposes therefore every resource that contains translatable fields
     * must define this value
     * @var string
     */
    protected $resourceName="";
        
    /**
     * Define a mutator so that translatable fields can be set as a property of the model
     * E.g.: payment_types contains a "type" field but it also requires a translatable 
     * field for displaying the type's name on screen, for this a property "name" can be defined
     * as the translatable field which is not stored in the payment_types table but rather in the resource_translations
     * table, but the value for this field can be set using the PaymentType model
     *      (new PaymentType)->name= "Credit Card";
     * 
     * @param type $name
     * @param type $value
     */
    public function __set($name, $value)
    {        
        if(isset($this->translated_fields[$name])){
            $this->translated_fields[$name] = $value;
        }
        else{
            parent::__set($name, $value);
        }
    }

    /**
     * Define an accessor to get properties from the model or from the translation table (resource_translations)
     * @param type $name
     * @return type
     */
    public function __get($name)
    {        
        $Resource= Resource::where("name",$this->resourceName)->first();
        if(isset($this->translated_fields[$name]) && isset($Resource)){
            $Translation= ResourceTranslation::where("resource_id", $Resource->id)
                    ->where("language_id", Language::getLanguage()->id)
                    ->where("field_name", $name)
                    ->first();
            $this->translated_fields[$name]= $Translation->field_value;
            return $this->translated_fields[$name];
        }
        else{
            return parent::__get($name);
        }        
    }
    
    /**
     * Override the save method so that the translatable fields can be saved into 
     * the resource_translation table, and the regular properties into the table corresponding
     * to the model's table
     * 
     * @param array $options
     */
    public function save(array $options = []){
        parent::save($options);

        $Resource= Resource::where("name",$this->resourceName)->first();
        if(isset($Resource)){
            //save all translatable fields
            foreach($this->translated_fields as $name=>$value){
                $Translation= ResourceTranslation::where("resource_id", $Resource->id)
                        ->where("language_id", Language::getLanguage()->id)
                        ->where("resource_instance_id", $this->id)
                        ->where("field_name", $name)
                        ->first();
                //when the field already exists in the resource_translations table
                //, update the value
                if(isset($Translation)){
                    $Translation->field_value= $value;
                    $Translation->save();
                }
                //create a new value when the field was not saved previously
                //into resource_translations table
                else{                    
                    $Translation= new ResourceTranslation();
                    $Translation->resource_id= $Resource->id;
                    $Translation->resource_instance_id= $this->id;
                    $Translation->language_id= Language::getLanguage()->id;
                    $Translation->field_name= $name;
                    $Translation->field_value= $value;
                    $Translation->save();
                }
            }
        }                
    }
    /**
     * Get the translation for a given language
     */
    public function translation($languageId = null){

        if(!isset($languageId)){
            $languageId = Language::getLanguage('nl')->id;            
        }

        $translationMethod = $this->translationMethod;

        // Get the translations in all languages through the defined relation
        $translations = $this->{$translationMethod};
        
        // Filter the one for the given language
        foreach($translations as $Translation){
            if($Translation->language_id == $languageId){
                $Item = $Translation;
            }
        }

        // Return the translation object
        return isset($Item) ? $Item : null;
    }
    
    /**
     * implementation of IResource::save(), to save the resource. It can also save (or not) fields with translations
     * @param array $data data to be save in the resource model with the format ['column_name' => 'value']
     * @param boolean $saveTranslation flag that tells whether to save translations or not
     * @param boolean $returnResource when it is true, returns the newly created object otherwise returns a string response
     * @return string result of the operation [ok|error|object]     * 
     */
    public function saveResource($data, $saveTranslation=true, $returnResource=false){
        
        
        foreach($data as $field => $value){
            if($field != 'id'){
                
                //set the value when the field is a valid model field
                if(Schema::hasColumn($this->getTable(), $field)){                    
                    $this->{$field}= $value;
                }                                
            }
        }
        
        if(parent::save()){            
            //save translation when translation exists
            if($saveTranslation){
                $this->saveTranslation($data);
            }
            
            if($returnResource){ return $this; } return "ok";
        }        
        if($returnResource){ return null; } return "error";
    }
    
    /**
     * Save the translatatable fields of the resource. It can receive the language to save to or it can just use the defaul locale
     * @param type $data array that contains the values for the fields in the corresponding translation table
     * @param type $lang language used to save the translation value or null to use the default locale
     */
    public function saveTranslation($data, $lang=null) {
                
        //Check whether a translation entity exists
        $translationClass=null;
        
        if(class_exists(static::class.'Translation')){
            //get translation class and foreing key field
            $translationClass= static::class.'Translation';
            
            //search for existing translation records
            $TranslationInstance= $this->translation();
            
            //when no translation record is found create a new one
            if(!isset($TranslationInstance) || !$TranslationInstance){
                $TranslationInstance= new $translationClass();
                $TranslationInstance->{$translationClass::$translatedModelFK}= $this->id;
                $TranslationInstance->language_id= Language::getLanguage($lang)->id;
            }
            
            foreach($data as $field => $value){
                if($field != 'id'){
                    //when the model has translation, save the corresponding 
                    //field to the translation table
                    if(isset($TranslationInstance) 
                            && Schema::hasColumn($TranslationInstance->getTable(), $field)){
                        $TranslationInstance->{$field}= $value;
                    }
                }
            }
            $TranslationInstance->save();
        }
    }
    
    /**
     * implementation of IResource::delete(), to delete the resource.
     * @return string
     */
    public function deleteResource(){
        if(parent::delete()){
            return "ok";
        }
        return "error";
    }
    /**
     * implementation of IResource::getList(), to get a list of the resource 
     * with the option to filter the list
     * @param array $filterOptions optional filter 
     * @param type $displayField column to show in the returned list
     * @return json resource list
     */
    public static function getList(Array $filterOptions, $displayField= 'name', $translationFile = null) {
       $data = [];
        $items = static::all();
        $displayFieldKey= isset($translationFile) ? trans('models/'.$translationFile.'.name_column.name') : 'name';
        foreach($items as $Item){
            $data[] = [
                'id' => $Item->id,
                $displayFieldKey => $Item->$displayField
            ];
        }
        return response()->json($data);
    }
    
    /**
     * Get the resource instace with the given id
     * @param type $id id of the resource to get
     * @return type \App\Models\AppModel
     */
    public static function get($id) {
        $Resource = static::find($id);
        return $Resource;
    }
    
    /**
     * Get options to be used in a dropdown element (<select>)
     * @param type $valueField corresponds to the value in <option value="">Text</option>
     * @param type $textField corresponds to the text in <option value="">Text</option>
     * @return array list of dropdown options
     */
    public static function dropdownOptions($valueField='id', $textField='name') {
        $records= static::all();
        
        $data=[
            [
                'value' => '',
                'text' => ''
            ]];
        foreach($records as $Record){
            $data[]= [
                'value' => $Record->$valueField,
                'text' => $Record->$textField
            ];
        }
        
        return $data;
    }
}
