<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoData extends Model
{
    /**
     * Get the categories for the video.
     */
    public function categories()
    {
        return $this->hasMany('App\VideoCategories');
    }
}
