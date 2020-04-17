<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoCategories extends Model
{
    
    /**
     * Each category entry has one datafield
     */
    public function data()
    {
        return $this->hasOne('App\Categories', 'id');
    }
}
