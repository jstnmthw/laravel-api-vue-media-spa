<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoCategories extends Model
{
    /**
     * Get the video that owns the video category.
     */
    public function video()
    {
        return $this->belongsTo('App\VideoData');
    }
    
    /**
     * Each category entry has one datafield
     */
    public function data()
    {
        return $this->hasOne('App\Categories', 'id');
    }

}
