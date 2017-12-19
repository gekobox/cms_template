<?php

namespace App\Policies;

use App\Product;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the product.
     *
     * @param  User  $user
     * @param  \App\Product  $product
     * @return mixed
     */
    public function read(User $user)
    {
        return $this->hasPermission($user, 'read');
    }

    /**
     * Determine whether the user can save products.
     *
     * @param  User  $user
     * @return mixed
     */
    public function save(User $user)
    {
        return $this->hasPermission($user, 'save');
    }

   

    /**
     * Determine whether the user can delete the product.
     *
     * @param  User  $user
     * @param  \App\Product  $product
     * @return mixed
     */
    public function delete(User $user)
    {
        return $this->hasPermission($user, 'delete');
    }
    
    public function hasPermission($user, $action, $modelName){
        $action= $action.'_action';
        $Model= \App\Models\Model::where('name',$modelName)->first();
        if(isset($Model)){
            $Permission= \App\Models\UserRolePermission::
                    where("user_role_id", $user->user_role_id)
                    ->where('model_id', $Model->id)->first();
            if(isset($Permission) && $Permission->$action == true){
                return true;
            }
        }
        return false;
    }
}
