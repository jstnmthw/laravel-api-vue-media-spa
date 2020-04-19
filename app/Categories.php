<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    /**
     * 
     */
    public function video_categories()
    {
        return $this->belongsToMany('App\VideoData', 'category_id');
    }
}
