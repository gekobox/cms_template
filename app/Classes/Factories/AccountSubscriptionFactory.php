<?php
namespace App\Classes\Factories;

use App\Models\AccountSubscription;

class AccountSubscriptionFactory extends ResourceFactoryAbstract{
    /**
     * Create and return an instance of App\Models\Account model
     * @return AccountPayment
     */
    public static function make(){
        return new AccountSubscription;
    }
}