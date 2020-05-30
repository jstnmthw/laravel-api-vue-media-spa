<?php

namespace App;

use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

class VideosIndex extends IndexConfigurator
{
    use Migratable;

    protected $name = 'videos_idx'; 

    /**
     * @var array
     */
    protected $settings = [
        //
    ];
}