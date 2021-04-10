<?php

namespace App;

use Spatie\Sitemap\Sitemap;

class MySitemap extends Sitemap
{
    /**
     * @return string
     */
    public function renderTags(): string
    {
        sort($this->tags);
        $tags = collect($this->tags)->unique('url')->filter();

        return view('laravel-sitemap::tags')
            ->with(compact('tags'))
            ->render();
    }
}
