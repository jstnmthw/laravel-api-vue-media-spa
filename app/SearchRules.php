<?php

namespace App;

use ScoutElastic\SearchRule;

class SearchRules extends SearchRule
{
    /**
     * @inheritdoc
     */
    public function buildHighlightPayload()
    {
        //
    }

    /**
     * @inheritdoc
     */
    public function buildQueryPayload()
    {
        return [
            'should' => [
                [
                    'match' => [
                        'title' => [
                            'query' => $this->builder->query,
                            'boost' => 1,
                        ],
                    ],
                ],
                [
                    'match' => [
                        'cateogires' => [
                            'query' => $this->builder->query,
                            'boost' => 3,
                        ],
                    ],
                ],
            ],
        ];
    }
}
