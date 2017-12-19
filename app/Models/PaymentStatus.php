<?php

namespace App\Models;

class PaymentStatus extends AppModel
{   
    public static $PAID= "paid";
    
    protected $resourceName="paymentStatus";
    protected $translated_fields=[
        "name"=>""
    ];
    protected $table= "payment_status";
    
}
