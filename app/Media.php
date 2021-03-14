<?php

namespace App;

use ElasticScoutDriverPlus\CustomSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Media extends Model
{
    use SoftDeletes, Searchable, CustomSearch, HasFactory;

    const DEFAULT_PAGE_SIZE = 50;

    /**
     * Get the index data array for the model.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        return $this->only([
            'id',
            'url',
            'external_key',
            'thumbnail',
            'album',
            'title',
            'categories',
            'author',
            'duration',
            'views',
            'likes',
            'dislikes',
            'created_at',
        ]);
    }
}
