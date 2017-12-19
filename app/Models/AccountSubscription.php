<?php

namespace App\Models;

use App\Classes\Interfaces\IResource;

class AccountSubscription extends AppModel
{
    public static $validationRules=[
        'account_id' => 'required|exists:accounts,id',
        'subscription_id' => 'required',        
    ];  
}
