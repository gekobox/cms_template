<?php

namespace App\Models;

use App\Classes\Interfaces\IResource;

class Warehouse extends AppModel
{       
    //Validation
    public static $validationRules=[        
        'name' => 'required',        
    ]; 
}
