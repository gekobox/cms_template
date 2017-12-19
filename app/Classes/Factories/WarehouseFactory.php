<?php
namespace App\Classes\Factories;

use App\Models\Warehouse;

class WarehouseFactory extends ResourceFactoryAbstract{
    /**
     * Create and return an instance of App\Models\Warehouse model
     * @return Warehouse
     */
    public static function make(){
        return new Warehouse();
    }
    
}

