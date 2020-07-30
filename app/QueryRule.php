<?php

namespace App;

use ScoutElastic\SearchRule;

class QueryRule extends SearchRule
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
                'function_score' => [
                    'query' => [
                        'bool' => [
                            'must' => [
                                'match' => [
                                    'title' => $this->builder->query,
                                ],
                            ],
                            'should' => [
                                'match' => [
                                    'categories' => $this->builder->query,
                                ],
                            ],
                        ],
                    ],
                    'field_value_factor' => [
                        'field' => 'likes',
                    ],
                ],
            ],
        ];
    }
}
