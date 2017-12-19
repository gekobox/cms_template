<?php

namespace App\Models;

use App\Classes\Interfaces\IResource;

class AddressType extends AppModel
{       
    public static $validationRules=[
        'name' =>'required'
    ];
}
