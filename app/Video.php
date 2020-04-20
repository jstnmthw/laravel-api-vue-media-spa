<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Video extends Model
{
    /**
     * Get the categories for the video.
     */
    public function categories()
    {
        return $this->morphToMany('App\Category', 'categorizable');
    }

}
