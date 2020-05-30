<?php

namespace App;

use ScoutElastic\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

// use Laravel\Scout\Searchable;

class Video extends Model
{
    use Searchable;

    protected $indexConfigurator = VideosIndex::class;

    protected $searchRules = [
        // ..
    ];

    protected $mapping = [
        'properties' => [
            'title' => [
                'type' => 'text'
            ],
            'category' => [
                'type' => 'text'
            ],
            'views' => [
                'type' => 'integer'
            ],
            'likes' => [
                'type' => 'integer'
            ],
            'dislikes' => [
                'type' => 'integer'
            ],
        ]
    ];

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    // public function searchableAs()
    // {
    //     return 'videos_idx';
    // }
    
    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    // public function toSearchableArray()
    // {
    //     $array = $this->toArray();

    //     return $array;
    // }

    /**
     * Get the categories for the video.
     */
    public function categories()
    {
        return $this->morphToMany('App\Category', 'categorizable');
    }

}
