<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedDefaultPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //create empty account for super user
        $SuperUserAccount= new \App\Models\Account();
        $SuperUserAccount->company= 'default';
        $SuperUserAccount->first_name= 'default';
        $SuperUserAccount->last_name= 'default';
        $SuperUserAccount->street= 'default';
        $SuperUserAccount->house_number= 0;
        $SuperUserAccount->postal_code= 'default';
        $SuperUserAccount->city= 'default';
        $SuperUserAccount->country_id= \App\Models\Country::first()->id;
        $SuperUserAccount->email= 'default@mail.com';
        $SuperUserAccount->is_active= true;
        $SuperUserAccount->save();
        
        //add timestamps
        Schema::table('models', function($table){
            $table->timestamps();
        });
        Schema::table('user_roles', function($table){
            $table->timestamps();
        });
        Schema::table('user_role_permissions', function($table){
            $table->timestamps();
        });
        
        //add models
        $models= ['Account', 'AccountPayment', 'AccountSubscription', 'AddressType', 'Attribute', 'Country', 'DataType'
            , 'Language', 'Model', 'Product', 'ProductAttributeValue', 'ProductImage', 'ProductSupplier'
            , 'Relation', 'RelationAddress', 'RelationContact', 'Supplier', 'SupplierAddress', 'SupplierContact'
            , 'UserRole', 'UserRolePermission' ,'Warehouse'];
        
        foreach ($models as $modelName){
            $Model= new \App\Models\Model();
            $Model->name= $modelName;
            $Model->save();
        }
        
        //add super user role and user
        
        $SuperUserRole= new App\Models\UserRole();
        $SuperUserRole->name= 'Vendata Super User';
        $SuperUserRole->save();
        
        $SuperUser= new \App\User();
        $SuperUser->user_role_id= $SuperUserRole->id;
        $SuperUser->name= 'Super user';
        $SuperUser->email= 'admin@mail.com';
        $SuperUser->password= bcrypt('123456');
        $SuperUser->account_id= $SuperUserAccount->id;
        $SuperUser->save();
        
        //grant full permissions to super user
        $models= \App\Models\Model::all();
        foreach ($models as $Model){
            $Permission= new App\Models\UserRolePermission();
            $Permission->user_role_id= $SuperUserRole->id;
            $Permission->model_id= $Model->id;
            $Permission->save_action = true;
            $Permission->read_action = true;
            $Permission->delete_action = true;
            $Permission->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
