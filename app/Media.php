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

    /**
     * Get the index data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return $this->only([
            'title',
            'thumbnail',
            'album',
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
