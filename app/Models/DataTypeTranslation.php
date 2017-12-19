<?php

namespace App\Models;

use App\Classes\Interfaces\IResource;

class DataTypeTranslation extends AppModel
{       
    //foreign key referencing the translated model
    public static $translatedModelFK= 'data_type_id';
}
