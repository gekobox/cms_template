<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Auth;

class SwitchDBConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {        
        $userDBIsSet= false;
        $User= Auth::user();
        
        //if(isset($User) && !$User->userRole->name == 'Vendata Super User'){
        if(isset($User)){
            //Get the account associated to the user
            $Account= $User->account;
            
            //Get the tenant database credentials from the account
            $tenant= [
               'database' => $Account->db_name,
               'db_user' => $Account->db_user,
               'db_password' => $Account->db_password
            ];

           //set the tenant db connection setting
           if(isset($tenant)){
               //when the db information for the user exists, switch the connection
               Config::set('database.connections.tenant.username', $tenant['db_user']);
               Config::set('database.connections.tenant.password', $tenant['db_password']);
               Config::set('database.connections.tenant.database', $tenant['database']);
               Config::set('database.default', 'tenant');
               \DB::purge('tenant');
               \DB::reconnect('tenant');
               $userDBIsSet=true;
               
           }   
        }        
        
        if(!$userDBIsSet){
               return response(['status' => 'Not Authorized'])
                    ->header('Content-Type', 'json');
        }
        
        return $next($request);
    }
}
