<?php
namespace App\Classes\Factories;

use App\Models\AccountPayment;

class AccountPaymentFactory extends ResourceFactoryAbstract{
    /**
     * Create and return an instance of App\Models\Account model
     * @return AccountPayment
     */
    public static function make(){
        return new AccountPayment;
    }
}