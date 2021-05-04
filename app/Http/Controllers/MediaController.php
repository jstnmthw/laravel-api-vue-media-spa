<?php

namespace App\Http\Controllers;

use App\Media;
use ElasticScoutDriverPlus\Builders\SearchRequestBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Str;
use RuntimeException;
use stdClass;

class MediaController extends Controller
{

    /**
     * Default Elastic Search document listing
     * @return LengthAwarePaginator
     */
    public function index(): LengthAwarePaginator
    {
        $data = Media::rawSearch()
            ->query([
                'bool' => [
                    'must_not' => [
                        'match' => [
                            'categories.name' => config('const.excluded_cats')
                        ]
                    ],
                    'must' => [
                        'range' => [
                            'views' => [
                                'gte' => 1000,
                                'boost' => 2.0
                            ]
                        ]
                    ]
                ],
            ])
            ->sort('created_at', 'desc');

        return $this->prepareDocs($data);
    }

    /**
     * Full text search for documents
     * @param Request $request
     * @return LengthAwarePaginator|void
     */
    public function search(Request $request): LengthAwarePaginator
    {
        //TODO: Exclude set categories from search.
        $query = $request->has('q')
            ? Str::lower(urldecode($request->input('q')))
            : abort(404);

        $data = Media::rawSearch()->query([
            'function_score' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            'match' => [
                                'title' => $query,
                            ]
                        ],
                        'must_not' => [
                            'match' => [
                                'categories' => config('const.excluded_cats')
                            ]
                        ],
                        'should' => [
                            'match' => [
                                'categories' => $query
                            ]
                        ],
                    ],
                ],
                'field_value_factor' => [
                    'field' => 'likes',
                    'factor' => 1.2,
                    'modifier' => 'sqrt',
                    'missing' => 1
                ],
            ],
        ]);

        return $this->prepareDocs($data);
    }

    /**
     * Return document by key
     * @param string $key
     * @return array
     */
    public function getByKey(string $key): array
    {
        $data = Media::matchSearch()
            ->field('unique_key')
            ->query($key)
            ->execute();

        return $data->total()
            ? $data->documents()->first()->getContent()
            : abort(404);
    }

    /**
     * Return documents by multiple ids
     * TODO: Should return by same order given
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function collect(Request $request): LengthAwarePaginator
    {
        $ids = explode(',',$request->input('ids')) ?? abort(404);
        $data = Media::idsSearch()
            ->values($ids);

        return $this->prepareDocs($data);
    }

    /**
     * Return documents based on percent of likes to dislikes
     * @return LengthAwarePaginator
     */
    public function best(): ?LengthAwarePaginator
    {
        $data = Media::rawSearch()->query([
            'function_score' => [
                'query' => [
                    'match_all' => new stdClass()
                ],
                'field_value_factor' => [
                    'field' => 'likes',
                    'factor' => 1.2,
                    'modifier' => 'sqrt',
                    'missing' => 1
                ],
            ],
        ]);

        return $this->prepareDocs($data);
    }

    /**
     * Return most likes docs
     * @return LengthAwarePaginator|null
     */
    public function mostLikes(): ?LengthAwarePaginator
    {
        $data = Media::rawSearch()
            ->query(['match_all' => new \stdClass()])
            ->sort('likes', 'desc');

        return $this->prepareDocs($data);
    }

    /**
     * Return most viewed docs
     * @return LengthAwarePaginator
     */
    public function mostViewed(): ?LengthAwarePaginator
    {
        $data = Media::rawSearch()
                ->query(['match_all' => new stdClass()])
                ->sort('views', 'desc');

        return $this->prepareDocs($data);
    }

    /**
     * Return related documents by Id
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function related(Request $request): LengthAwarePaginator
    {
        $data = Media::rawSearch()
            ->query([
                'more_like_this' => [
                    'fields' => [
                        'title',
                        'categories'
                    ],
                    'like' => [
                        '_id' => $request->input('id')
                    ],
                    'min_term_freq' => 1,
                    'max_query_terms' => 12
                ]
            ]);

        return $this->prepareDocs($data, 12);
    }

    /**
     * Return documents by category
     * @param $slug
     * @return LengthAwarePaginator
     */
    public function category($slug): LengthAwarePaginator
    {
        $category = Str::title($slug);
        $data = Media::rawSearch()->query([
            'function_score' => [
                "query" => [
                    "match" => ['categories.name' => $category]
                ],
                'field_value_factor' => [
                    'field' => 'likes',
                    'factor' => 1.2,
                    'modifier' => 'sqrt',
                    'missing' => 1
                ],
            ],
        ]);

        return $this->prepareDocs($data);
    }

    /**
     * Increment likes column
     * @param $id
     * @return JsonResponse
     */
    public function like($id): JsonResponse
    {
        if (Media::query()->where('id', $id)->increment('likes')) {
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }

    /**
     * Increment dislikes column
     * @param $id
     * @return JsonResponse
     */
    public function dislike($id): JsonResponse
    {
        if (Media::query()->where('id', $id)->increment('dislikes')) {
            return response()->json(['success' => 1]);
        }
        return response()->json(['success' => false], 404);
    }

    /**
     * Prepare only elasticsearch documents with pagination.
     * Note: Pulling documents straight from elasticsearch means no added MySQL query.
     *
     * @param SearchRequestBuilder $documents
     * @param int $perPage
     * @param string $pageName
     * @param int|null $page
     * @return LengthAwarePaginator|void
     */
    public function prepareDocs(
        SearchRequestBuilder $documents,
        int $perPage = Media::DEFAULT_PAGE_SIZE,
        string $pageName = 'page',
        int $page = null
    ): LengthAwarePaginator
    {
        $page = $page ?? Paginator::resolveCurrentPage($pageName);

        $data = $documents
            ->from(($page - 1) * $perPage)
            ->size($perPage)
            ->execute();

        if (is_null($data->total())) {
            throw new RuntimeException(
                'Search result does not contain the total hits number. ' .
                'Please, make sure that total hits are tracked.'
            );
        }

        if ($data->total() === 0) {
            abort(404);
        }

        $dataArr = [];
        foreach ($data->documents() as $row) {
            $dataArr[] = $row->getContent();
        }

        return new LengthAwarePaginator(
            $dataArr,
            $data->total(),
            $perPage,
            $page,
            [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => 'page',
            ]
        );
    }

}
