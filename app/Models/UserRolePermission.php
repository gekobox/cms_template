<?php

namespace App\Models;

use App\Classes\Interfaces\IResource;

class UserRolePermission extends AppModel
{       
    public function model(){
        return $this->belongsTo('App\Models\Model');
    }
}
