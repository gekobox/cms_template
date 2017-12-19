<?php

namespace Tests\Unit\Resources;

use App\Classes\Factories\ProductFactory;
use App\Models\Product;
use App\Models\Supplier;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testProductCreation()
    {
        $supplier= Supplier::first();
        $data=[
            "supplier_id" => $supplier->id,
            "name" => "Test Product",
            "sku" => "234",
            "ean" => "54354",
            "price" => 10.5            
        ];        
        $response= $this->post('/api/save-resource/product', $data);
        $response->assertStatus(200);
        $response->assertJson(["saved" => "ok"]);
    }
    /**
     * @depends testProductCreation
     */
    public function testProductDelete(){
        $Product= Product::where('name', "Test Product")->first();
        $Product->_delete();
    }
}
