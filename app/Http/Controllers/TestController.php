<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\ResourceManager;

class TestController extends Controller
{
    public function test1(Request $request){
        $Language = ResourceManager::make('Language');
        $data = [
            'code' => 'en',
            'name' => 'English'
        ];
        $Language->saveResource($data);
    }
    
    public function getAccounts(){
        $accounts= \App\Models\Account::all();
        return [$accounts];
    }
}
