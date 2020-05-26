<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    
    /**
     * Get all of the videos that are assigned this category.
     */
    public function videos()
    {
        return $this->morphedByMany('App\Video', 'categorizable');
    }

    /**
     * Get the parent of the category.
     */
    public function parentable()
    {
        return $this->morphedByMany();
    }
    
}
