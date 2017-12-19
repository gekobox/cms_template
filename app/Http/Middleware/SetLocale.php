<?php

namespace App\Http\Middleware;

use App;
use Closure;
use Session;
use Config;

class SetLocale
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
        if ($request->session()->has('locale')) {
            $locale = $request->session()->get('locale', Config::get('app.locale'));
        } else {
            $locale = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);

            // Set the default as active language
            if (!in_array($locale, Config::get('app.availableLanguages'))) {
                $locale = Config::get('app.defaultLanguage');
            }
        }

        App::setLocale($locale);

        return $next($request);
    }
}
