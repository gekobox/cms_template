<?php

namespace App\Models;

use App;
use App\Classes\Interfaces\IResource;

class Language extends AppModel
{       
    /**
     * Return the current language or the language corresponding to the 
     * given parameter
     */
    public static function getLanguage($lang=null){
        if(isset($lang)){
            $currentLang= $lang;
        }
        else{
            $currentLang= App::getLocale();
        }
        
        $Language = static::where('code', '=', $currentLang)->first();
        return $Language;
    }
}
