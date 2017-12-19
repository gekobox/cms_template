<?php

namespace App\Policies;

use App\User;


class RelationContactPolicy extends BasePolicy
{    
    protected $modelName= 'RelationContact';
    /**
     * Determine whether the user can view the product.
     *
     * @param  User  $user
     * @param  \App\Product  $product
     * @return mixed
     */
    public function read(User $user)
    {
        return $this->hasPermission($user, 'read', $this->modelName);
    }

    /**
     * Determine whether the user can save products.
     *
     * @param  User  $user
     * @return mixed
     */
    public function save(User $user)
    {
        return $this->hasPermission($user, 'save', $this->modelName);
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
        return $this->hasPermission($user, 'delete', $this->modelName);
    }    
}
