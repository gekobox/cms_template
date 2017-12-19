<?php

namespace App\Models;

use App\Classes\Interfaces\IResource;

class AccountPayment extends AppModel
{
    public static $validationRules=[
        'account_id' => 'required|exists:accounts,id',
        'payment_id' => 'required',        
    ];    
    
}
