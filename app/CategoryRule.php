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
            // TODO: Need to group categories in the future.
            // 'must_not' => [
            //     'match' => [
            //         'categories' => config('const.excluded_cats'),
            //     ],
            // ],
        ];
    }
}
