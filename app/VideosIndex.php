<?php

namespace App;

use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

class VideosIndex extends IndexConfigurator
{
    use Migratable;

    // Index name for Elastic search index
    protected $name = 'videos_idx';

    /**
     * Elastic Search settings
     *
     * @var array
     * @return json
     */
    protected $settings = [
        'analysis' => [
            'analyzer' => 'standard',
        ],
    ];
}
