<?php

namespace App\Http\Middleware;

use Closure;

class CheckDeletePermission
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
            \App\Classes\ResourceManager::authorize('delete', $resourceType);
        }
        return $next($request);
    }
}
