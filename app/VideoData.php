<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class VideoData extends Model
{
    /**
     * Get the categories for the video.
     */
    public function categories()
    {
        return $this->hasMany('App\VideoCategories');
    }

    /**
     * Get the parent of the activity feed record.
     */
    public function parentable()
    {
        return $this->morphTo();
    }
}
