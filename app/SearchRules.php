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
    // public function buildQueryPayload()
    // {
    //     return [
    //         'should' => [
    //             [
    //                 'match_phrase_prefix' => [
    //                     'title' => [
    //                         'query' => $this->builder->query,
    //                     ],
    //                 ],
    //             ],
    //             [
    //                 'match' => [
    //                     'categories' => [
    //                         'query' => $this->builder->query,
    //                         'boost' => 10,
    //                     ],
    //                 ],
    //             ],
    //             [
    //                 'range' => [
    //                     'views' => [
    //                         'gte' => 1000,
    //                         'boost' => 10,
    //                     ],
    //                 ],
    //                 'range' => [
    //                     'views' => [
    //                         'gte' => 10000,
    //                         'boost' => 20,
    //                     ],
    //                 ],
    //                 'range' => [
    //                     'views' => [
    //                         'gte' => 100000,
    //                         'boost' => 30,
    //                     ],
    //                 ],
    //                 'range' => [
    //                     'views' => [
    //                         'gte' => 1000000,
    //                         'boost' => 40,
    //                     ],
    //                 ],
    //                 'range' => [
    //                     'views' => [
    //                         'gte' => 10000000,
    //                         'boost' => 50,
    //                     ],
    //                 ],
    //             ],
    //         ],
    //         'must_not' => [
    //             'match' => [
    //                 'categories' => '',
    //             ],
    //         ],
    //     ];
    // }
}
