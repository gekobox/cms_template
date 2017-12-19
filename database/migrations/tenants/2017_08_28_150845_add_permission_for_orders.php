<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPermissionForOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $Model= App\Models\Model::where('name','Order')->first();
        $SuperRole= App\Models\UserRole::where('name', 'Vendata Super User')->first();
        if(!isset($Model)){
            $Model= new App\Models\Model();
            $Model->name='Order';
            $Model->save();
        }
        
        $Permission= new App\Models\UserRolePermission();
        $Permission->user_role_id= $SuperRole->id;
        $Permission->model_id= $Model->id;
        $Permission->save_action=1;
        $Permission->read_action=1;
        $Permission->delete_action=1;
        $Permission->save();
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
