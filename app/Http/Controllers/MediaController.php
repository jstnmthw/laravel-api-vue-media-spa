<?php

namespace App\Http\Controllers;

use App\Media;
use ElasticScoutDriverPlus\Builders\SearchRequestBuilder;
use ElasticScoutDriverPlus\SearchResult;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use\Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use RuntimeException;

class MediaController extends Controller
{

    /**
     * Default Elastic Search document listing
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function index(Request $request) {
        return $this->prepareDocs(Media::matchAllSearch());
    }

//    public function index(Request $request)
//    {
//
//        // Sorting
//        $sort = 'most_views';
//        if ($request->has('sort_by')) {
//            switch ($request->input('sort_by')) {
//                case 'most_views':
//                    $sort = 'views';
//                    break;
//                case 'duration':
//                    $sort = 'duration';
//                    break;
//                case 'most_recent':
//                    $sort = 'created_at';
//                    break;
//            }
//        }
//
//        // Model by ID
//        if ($request->has('id')) {
//            $data = Media::query()->where('id', $request->input('id'))->firstOrFail();
//
//            // TODO: Transform data at insert.
//            preg_match(config('regex.domain'), $data->embed, $url);
//            $data->embed = $url[0];
//
//            // Explode Categories
//            $data->categories = explode(';', $data->categories);
//
//            return $data;
//        }
//
//        // Collection of models
//        if ($request->has('collection')) {
//            // TODO: Make sure array is not over 50 in order to limit query.
//            $collection = explode(',', $request->input('collection'));
//            return Media::query()->whereIn('id', $collection)->get();
//        }
//
//        // Category model listing
//        if ($request->has('category')) {
//            return Media::search($request->input('category'))
//                ->rule(CategoryRule::class)
//                ->orderBy('views', 'DESC')
//                ->paginate($this->limit);
//        }
//
//        // Search model listing
//        if ($request->has('q') && !empty($request->input('q'))) {
//            $data = Media::search($request->input('q'))->rule(QueryRule::class);
//            $data->whereNotMatch('categories', config('const.excluded_cats'));
//
//            if ($request->has('exclude')) {
//                $data->whereNotIn('id', [$request->input('exclude')]);
//            }
//
//            if ($request->has('sort_by')) {
//                $data->orderBy($sort, 'DESC');
//            }
//
//            return $data->paginate($this->limit);
//        }
//
//        // Most Views
//        if ($request->has('most_viewed')) {
//            return Media::search('*')
//                ->orderBy('views', 'desc')
//                ->paginate($this->limit);
//        }
//
//        // Default model listing
//        return Media::search('*')
//            ->whereNotMatch('categories', config('const.excluded_cats'))
//            ->orderBy('views', 'desc')
//            ->paginate($this->limit);
//    }

    /**
     * Return document by title
     * @param Request $request
     * @param string $slug
     * @return array
     */
    public function title(Request $request, string $slug) {
        $title = str_replace('-', ' ', $slug);
        $data = Media::boolSearch()
            ->must('match', ['title.alphanumeric' => $title])
            ->execute()
            ->documents()
            ->first();

        return array_merge(['id' => (int) $data->getId()], $data->getContent());
    }

    /**
     * Return weekly top documents
     * @param Request $request
     * @return LengthAwarePaginator|null
     */
    public function best(Request $request) {
        $data = Media::rangeSearch()
                ->field('created_at')
                ->gt(now()->subWeek())
                ->size($this->pageLimit($request))
                ->execute();

        return $this->prepareDocs($request, $data);
    }

    /**
     * Return related documents by Id
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function related(Request $request) {
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
            ])
            ->size($this->pageLimit($request))
            ->execute();

        return $this->prepareDocs($request, $data);
    }

    /**
     * Increment likes column
     * @param $id
     * @return JsonResponse
     */
    public function like($id) {
        if (Media::query()->where('id', $id)->increment('likes')) {
            return response()->json(['success' => true], 200);
        }
        return response()->json(['success' => false], 404);
    }

    /**
     * Increment dislikes column
     * @param $id
     * @return JsonResponse
     */
    public function dislike($id) {
        if (Media::query()->where('id', $id)->increment('dislikes')) {
            return response()->json(['success' => 1], 200);
        }
        return response()->json(['success' => false], 404);
    }

    /**
     * Set default page limit
     * @param $request
     * @return int
     */
    public function pageLimit($request) {
        return ($request->has('limit') && $request->input('limit') < 50) ? (int) $request->input('limit') : 50;
    }

    /**
     * Prepare only elasticsearch documents with pagination.
     * Note: Pulling documents straight from elasticsearch means no added MySQL query.
     *
     * @param SearchRequestBuilder $documents
     * @param int $perPage
     * @param string $pageName
     * @param int|null $page
     * @return LengthAwarePaginator
     */
    public function prepareDocs(
        SearchRequestBuilder $documents,
        int $perPage = Media::DEFAULT_PAGE_SIZE,
        string $pageName = 'page',
        int $page = null
    ) {
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
