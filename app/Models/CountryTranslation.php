<?php

namespace App\Models;

use App\Classes\Interfaces\IResource;

class CountryTranslation extends AppModel
{       
    //foreign key referencing the translated model
    public static $translatedModelFK= 'country_id';
}
