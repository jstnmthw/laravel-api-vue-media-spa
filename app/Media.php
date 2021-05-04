<?php

namespace App;

use ElasticScoutDriverPlus\CustomSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

/**
 * @property mixed $unique_key Unique key for from external source
 * @property string $title Title of media source
 * @property string $slug (Accessor) Encoded slug for url
 * @property int $id Primary key auto increment
 * @property string categories Media's categories
 */

class Media extends Model
{
    use SoftDeletes, Searchable, CustomSearch, HasFactory;

    const DEFAULT_PAGE_SIZE = 50;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url',
        'unique_key',
        'thumbnail',
        'album',
        'title',
        'categories',
        'author',
        'duration',
        'views',
        'likes',
        'dislikes',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'slug',
    ];

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
            'unique_key',
            'thumbnail',
            'album',
            'title',
            'slug',
            'categories',
            'author',
            'duration',
            'views',
            'likes',
            'dislikes',
            'created_at',
        ]);
    }

    /**
     * Slug accessor
     *
     * @return string
     */
    public function getSlugAttribute(): string
    {
        return $this->cleanUrl($this->title). '-' . $this->unique_key;
    }

    /**
     * Trim title accessor
     *
     * @param $value
     * @return string
     */
    public function getTitleAttribute($value): string
    {
        return trim($value);
    }

    /**
     * Categories accessor
     *
     * @param $value
     * @return array
     */
    public function getCategoriesAttribute($value): array
    {
        $categories = explode(';', $value);
        return array_map(function($v) {
            return array(
                'name' => $v,
                'url' => $this->cleanUrl($v)
            );
        }, $categories);
    }

    /**
     * Cleans string and adds hyphens
     * @param String $string
     * @return string
     */
    private function cleanUrl(String $string): string
    {
        return preg_replace('/\s/','-', trim(preg_replace('/[^\\p{L} 0-9]/um', '', strtolower($string))));
    }
}
