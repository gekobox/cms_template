<?php

namespace App\Http\Controllers;

use App\Classes\Helpers\TenantHelper;
use App\Classes\ResourceManager;
use App\Models\Country;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class AccountsController extends Controller
{
    /**
     * Creates an account with the data received from the registration form 
     * from the Website
     * 
     * @param Request $request
     * @return string
     */
    public function createAccount(Request $request){
        
        $this->validate($request, ResourceManager::getValidationRules('Account'));

        //get country
        if(isset($request->country_code)){
            $Country= Country::where('code', $request->country_code)->first();
        }
        
        if(!isset($Country)){
            $Country= Country::first();
        }
        
        //create a new Account
        $Account= ResourceManager::make('Account');        
        $data['company'] = isset($request->company) ? $request->company : '';
        $data['first_name'] = isset($request->first_name) ? $request->first_name : '';
        $data['last_name'] = isset($request->last_name) ? $request->last_name : '';
        $data['street'] = isset($request->street) ? $request->street : '';
        $data['house_number'] = isset($request->house_number) ? $request->house_number : 0;
        $data['house_number_addition'] = isset($request->house_number_addition) ? $request->house_number_addition : '';
        $data['postal_code'] = isset($request->postal_code) ? $request->postal_code : '';
        $data['city'] = isset($request->city) ? $request->city : '';
        $data['country_id'] = $Country->id;
        $data['phone'] = isset($request->phone) ? $request->phone : '';
        $data['email'] = isset($request->email) ? $request->email : '';
        $data['payment_customer_id'] = isset($request->payment_customer) ? $request->payment_customer_id : '';
        $data['is_active'] = isset($request->is_active) ? $request->is_active : 0;
        $data['company'] = isset($request->company) ? $request->company : '';
        
        $Account->saveResource($data);
        
        //create first admin user for the account
        $User= new User();
        $User->email= $request->email;
        $User->password= bcrypt($request->password);
        $User->name= isset($request->admin_name) ? $request->admin->name : isset($request->company) ? $request->company : '';
        $User->account_id= $Account->id;
        $User->save();
        
        $response['status']= 'fail';
        //create the tenant db
        if(TenantHelper::newTenant($Account)){
            $response['status']= 'account created';
        }
        
        return $response;
    }
    
    /**
     * Create user from a tenant's connection
     */
    public function createUser(Request $request){
        $this->validate($request, User::$validationRules);
        $accountId= Auth::user()->account->id;
        
        //switch connection to the shared db
        Config::set('database.default', 'mysql');
        \DB::purge('mysql');
        \DB::reconnect('mysql');
        
        //create first admin user for the account
        $User= new User();
        $User->email= $request->email;
        $User->password= bcrypt($request->password);
        $User->name= isset($request->name) ? $request->name : '';
        $User->account_id= $accountId;
        $User->save();
                        
        return ['status' => 'USER_CREATED'];
    }
    
    public function getUserAccountId(){
        $User= Auth::user();
        return ['account_id' => $User->account_id];
    }
}
