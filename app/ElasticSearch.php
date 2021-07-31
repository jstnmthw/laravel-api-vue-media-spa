<?php

namespace App;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

class ElasticSearch
{
    private Client $es;
    const index = 'media';

    public function __construct()
    {
        $this->es = ClientBuilder::create()
            ->setHosts([env('ELASTIC_HOST', 'localhost:9200')])
            ->build();
    }

    public function client(): Client
    {
        return $this->es;
    }

    public function body(Array $body)
    {
        $params = [
            'index' => self::index,
            'size' => 25000,
            'body' => $body,
            'track_total_hits' => true
        ];
        return $this->es->search($params);
    }
}