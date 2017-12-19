<?php

namespace App\Models;

use App\Classes\Interfaces\IResource;

class OrderLine extends AppModel
{       
    public function product(){
        return $this->belongsTo('\App\Models\Product');
    }
}
