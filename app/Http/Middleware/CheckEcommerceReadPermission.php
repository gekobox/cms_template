<?php

namespace App\Http\Middleware;

use App\Classes\ResourceManager;
use Closure;
use Illuminate\Support\Facades\Log;

class CheckEcommerceReadPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        $resourceType= $request->route()->parameters['resourceType'];
        if(isset($resourceType)){
            if(!ResourceManager::authorizeEcommerce('read', $resourceType)){
                return response("Unauthorized", 403);
            }
        }
        return $next($request);
    }
}
