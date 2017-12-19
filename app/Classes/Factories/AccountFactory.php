<?php
namespace App\Classes\Factories;

use App\Models\Account;

class AccountFactory extends ResourceFactoryAbstract{
    /**
     * Create and return an instance of App\Models\Account model
     * @return Account
     */
    public static function make(){
        return new Account;
    }
}