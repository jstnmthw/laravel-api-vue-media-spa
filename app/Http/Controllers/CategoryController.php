<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Category;
use App\Media;

class CategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {

        $data = Media::rawSearch()
            ->query(['match_all' => new \stdClass()])
            ->size(0)
            ->aggregateRaw([
                'categories' => [
                    'terms' => [
                        'field' => 'categories.name.keyword',
                        'size' => 1000,
                        'order' => [
                            '_key' => 'asc'
                        ]
                    ],
                ],
            ])
            ->execute()
            ->aggregations()
            ->get('categories');

        array_shift($data['buckets']);
        array_pop($data['buckets']);

        $categories = [];
        foreach ($data['buckets'] as $category) {
            if (!preg_match('/Test/', $category['key'])) {
                $categories[] = [
                    'name' => $category['key'],
                    'url' => $this->cleanUrl($category['key']),
                    'count' => $category['doc_count'],
                ];
            }
        };

        return response()->json($categories);

    }

    /**
     * Cleans i18n string and adds hyphens
     * @param String $string
     * @return string
     * @noinspection PhpUnnecessaryLocalVariableInspection
     */
    private function cleanUrl(String $string): string
    {
        $string = trim($string);
        $string = strtolower($string);
        $string = preg_replace('/[\/\s]/','-', $string); // replace spaces and forward slashes with hyphen
        $string = preg_replace('/[^\\p{L} 0-9\\-]/um', '', $string); // replace any special characters excluding i18n characters and hyphen
        return $string;
    }

}
