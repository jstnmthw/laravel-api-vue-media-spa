<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property String name
 */

class Category extends Model
{
    protected $appends = [
      'url'
    ];

    public function getNameAttribute($value): string
    {
        return trim($value);
    }

    /**
     * Category Url
     *
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return preg_replace('/\s/','-', trim(preg_replace('/[^\\p{L} 0-9]/um', '', strtolower($this->name))));
    }
}