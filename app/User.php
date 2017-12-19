<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    // relationships
    public function account(){
        return $this->belongsTo('App\Models\Account');
    }
    
    public function userRole(){
        return $this->belongsTo('App\Models\UserRole');
    }
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    /**
     * Validation rules
     * @var type 
     */
    public static $validationRules=[
        'email' => 'required|email|unique:accounts,email',
        'name' => 'required',        
        'password' => 'required'
    ];
}
