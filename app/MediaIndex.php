<?php

namespace App;

use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

class MediaIndex extends IndexConfigurator
{
    use Migratable;

    // Elastic search index
    protected $name = 'media_idx';

    /**
     * Elastic Search settings
     * @var array
     */
    protected $settings = [
        'analysis' => [
            'analyzer' => 'standard',
        ],
    ];
}
