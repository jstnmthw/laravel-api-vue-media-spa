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
                    'match_phrase_prefix' => [
                        'title' => [
                            'query' => $this->builder->query,
                        ],
                    ],
                ],
                [
                    'match' => [
                        'categories' => [
                            'query' => $this->builder->query,
                            'boost' => 2,
                        ],
                    ],
                ],
                [
                    'range' => [
                        'views' => [
                            'gte' => 100000,
                            'boost' => 3,
                        ],
                    ],
                ],
            ],
            'must_not' => [
                'match' => [
                    'categories' => 'gay',
                ],
            ],
        ];
    }
}
