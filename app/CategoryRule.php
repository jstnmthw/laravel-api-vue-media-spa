<?php

namespace App;

use ScoutElastic\SearchRule;

class CategoryRule extends SearchRule
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
            'must' => [
                'match' => [
                    'categories' => $this->builder->query,
                ],
            ],
            'must_not' => [
                'match' => [
                    'categories' => '',
                ],
            ],
        ];
    }
}
