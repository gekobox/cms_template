<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UserRolePermission;
use Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUserPermissions(Request $request){
        $User= Auth::user();

        $permissions=[];
        if(isset($User)){
            $userPermissions= UserRolePermission::where('user_role_id', $User->user_role_id)->get();
            foreach($userPermissions as $UserPermission){
                $permissions[lcfirst($UserPermission->model->name)] = ($UserPermission->read_action) ? true : false;
            }
        }
        return $permissions;
    }
    
    public function getNavigationMenu(Request $request){                        
        $permissions= $this->getUserPermissions($request);        
        return view('elements.side-nav-links', ['permissions' => $permissions])->render();
    }
}
