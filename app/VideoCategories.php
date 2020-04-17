<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoCategories extends Model
{
    
    /**
     * Video's have many categories
     */
    public function category()
    {
        return $this->hasMany('App\Categories');
    }
}
