<?php

namespace App;

use ScoutElastic\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

use App\TextSearchRule;

// use Laravel\Scout\Searchable;

class Video extends Model
{
    use Searchable;

    protected $indexConfigurator = VideosIndex::class;

    protected $searchRules = [
        TextSearchRule::class
    ];

    protected $mapping = [
        'properties' => [
            'title' => [
                'type' => 'text',
                'fields' => [
                    'raw' => [
                        'type' => 'keyword'
                    ]
                ]
            ]
        ]
    ];

    /**
     * Get the categories for the video.
     */
    public function categories()
    {
        return $this->morphToMany('App\Category', 'categorizable');
    }

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'videos_idx';
    }

}
