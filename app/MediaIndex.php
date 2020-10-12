<?php

namespace App;

use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

class MediaIndex extends IndexConfigurator
{
    use Migratable;

    // Index name for Elastic search index
    protected $name = 'media_idx';

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
