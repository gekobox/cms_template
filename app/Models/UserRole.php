<?php

namespace App\Models;

use App\Classes\Interfaces\IResource;

class UserRole extends AppModel
{       
    public function userRolePermissions(){
        return $this->hasMany('App\Models\UserRolePermission');
    }
    
}
