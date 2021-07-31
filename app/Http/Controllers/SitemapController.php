<?php

namespace App\Http\Controllers;

use App\Media;
use App\ElasticSearch;

class SitemapController extends Controller
{
    public function media($page)
    {

        $es = new ElasticSearch();

        $count = $es->client()->count()['count'];
        dd($count);
        $limit = 15000;
        $total = $count / $limit;

        $offset = ($page * $limit) - $limit;
        $next = $offset + $limit;

        $params = [
            'track_total_hits' => true,
            'query' => [
                'range' => [
                    'id' => [
                        'gte' => $offset,
                        'lte' => $next
                    ]
                ]
            ]
        ];

        dd($es->body($params));

//{
//    "query": {
//    "range": {
//        "id": {
//            "gte": 745000,
//        "lte": 745040
//      }
//    }
//  }
//}

        dd(intval(ceil($total)));

    }
}
