<?php

namespace App\Models;

use App\Classes\Interfaces\IResource;

class Relation extends AppModel
{       
    //Validation
    public static $validationRules=[
        'is_customer' => 'required',
        'is_business' => 'required',        
        'name' => 'required'
    ]; 
    
    public function relationContacts(){
        return $this->hasMany('\App\Models\RelationContact');
    }
    
    /**
     * Override get method from IResource
     * @param type $id
     * @return type
     */
    public static function get($id) {
        //get the relation with the related relation contact
        $Resource = static::with('relationContacts')->find($id);
        //format the boolean values
        $Resource->is_customer= ($Resource->is_customer) ? true: false;
        $Resource->is_business= ($Resource->is_business) ? true: false;
        return $Resource;
    }
}
