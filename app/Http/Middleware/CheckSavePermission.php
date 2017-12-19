<?php

namespace App\Http\Middleware;

use Closure;

class CheckSavePermission
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
            \App\Classes\ResourceManager::authorize('save', $resourceType);
        }
        return $next($request);
    }
}
