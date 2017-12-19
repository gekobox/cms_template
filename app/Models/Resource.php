<?php

namespace App\Models;

class Resource extends \Illuminate\Database\Eloquent\Model
{       
    public function translation(){
        return $this->hasMany("\App\Models\ResourceTranslation");
    }
}
