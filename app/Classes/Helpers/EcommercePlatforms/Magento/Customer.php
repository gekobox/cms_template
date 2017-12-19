<?php
namespace App\Classes\Helpers\EcommercePlatforms\Magento;

class Customer {
    public static $resources=[
        //API's main endpoint for the resource
        "endpoint" =>"/rest/V1/customers/",
        //API's endpoint to search a resource
        "search_endpoint" =>"/rest/V1/customers/search",
        //fields mapping
        "fields" =>[
            // [vendata fields] => [magento mapped fields]
            "code" => "id",
            "first_name" => "firstname",
            "last_name" => "lastname",
            "email" => "email",
            "phone" => ""
        ],
        //Used to create the search criteria
        "searchFields" =>[            
                "firstname", "lastname", "email"            
        ]        
    ];
    
    /**
     * format the result that comes from an API request in order to show on Vendata's frontend
     * @param type $item
     * @param type $attributes
     * @return type
     */
    public static function getFormattedItem($item, $attribute=null){
        
        $formattedItem= [                    
            "code"=> $item["id"],
            "name" => $item["firstname"]. $item["lastname"],
            "first_name" => $item["firstname"],
            "last_name" => $item["lastname"],
            "phone" => $item["addresses"][0]["telephone"],
            "email" => $item["email"],
            "is_customer" =>true,
            "is_business" =>false,
            "notes"=>""
        ];

        return $formattedItem;
    }
}