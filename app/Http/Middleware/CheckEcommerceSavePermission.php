<?php

namespace App\Http\Middleware;

use Closure;

class CheckEcommerceSavePermission
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
            \App\Classes\ResourceManager::authorizeEcommerce('save', $resourceType);
        }
        return $next($request);
    }
}
