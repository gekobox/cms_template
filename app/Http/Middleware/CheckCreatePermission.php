<?php

namespace App\Http\Middleware;

use Closure;

class CheckCreatePermission
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
            \App\Classes\ResourceManager::authorize('create', $resourceType);
        }
        return $next($request);
    }
}
