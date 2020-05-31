<?php

namespace App;

use ScoutElastic\SearchRule;

class TextSearchRule extends SearchRule
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
            "must" => [
                "match_phrase" => [
                    "title" => $this->builder->query
                ]
            ]
        ];
    }

}