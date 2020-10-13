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

    protected $mapping = [
        'properties' => [
            'title' => [
                'type' => 'text',
                'fields' => [
                    'raw' => [
                        'type' => 'keyword',
                    ],
                ],
            ],
        ],
    ];

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'videos_idx';
    }

    /**
     * Get the index data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return $this->only([
            'title',
            'categories',
            'views',
            'likes',
            'dislikes',
            'duration',
            'author',
            'created_at',
            'updated_at',
        ]);
    }
}
