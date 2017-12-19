<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Config;

class TranslationsController extends Controller
{
    public function changeLocale($locale)
    {
        if (!in_array($locale, Config::get('app.availableLanguages'))) {
            abort('404');
        }

        // Store the chosen language
        Session::put('locale', $locale);

        // Redirect to the previous page
        return redirect()->back();
    }
}
