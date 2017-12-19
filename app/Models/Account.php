<?php

namespace App\Models;

use App\Classes\Interfaces\IResource;

class Account extends AppModel
{
    public static $validationRules=[
        'email' => 'required|email|unique:accounts,email',
        'company' => 'required'
    ];
}
