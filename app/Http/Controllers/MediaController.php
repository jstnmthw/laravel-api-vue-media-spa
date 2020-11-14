<?php

namespace App\Http\Controllers;

use App\Media;
use ElasticScoutDriverPlus\SearchResult;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use\Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class MediaController extends Controller
{
    private $limit;

    public function __construct(Request $request)
    {
        // Set default limit
        $this->limit = $this->page_limit($request);
    }

    /**
     * Default Elastic Search document listing
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function index(Request $request)
    {
        $data = Media::matchAllSearch()
            ->size($this->limit)
            ->execute();

        return $this->prepare_docs($request, $data);
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
     * Elastic Search document by ID
     * @param $id
     * @return Collection|void
     */
    public function get($id) {
        $data = Media::idsSearch()
            ->values([$id])
            ->execute()
            ->documents();

        return $data->isNotEmpty()
            ? $data->first()->getContent()
            : abort(404);
    }

    /**
     * Return top documents by week
     * @param Request $request
     * @return LengthAwarePaginator|null
     */
    public function best(Request $request)
    {
        $data = Media::rangeSearch()
                ->field('created_at')
                ->size(5)
                ->gt(now()->subWeek())
                ->execute();

        return $this->prepare_docs($request, $data);
    }

    /**
     * Increment likes column on
     * @param $id
     * @return JsonResponse
     */
    public function like($id)
    {
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
    public function dislike($id)
    {
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
    public function page_limit($request) {
        return ($request->has('limit') && $request->input('limit') < 50) ? (int) $request->input('limit') : 50;
    }

    /**
     * Prepare Elastic Search documents with paginate
     * @param Request $request
     * @param $matches
     * @return LengthAwarePaginator|null
     */
    public function prepare_docs(Request $request, $matches) {
        if($matches instanceof SearchResult) {
            $docs = $matches->documents();
            $res = [];
            foreach ($docs as $media) {
                $res[] = array_merge(['id' => (int) $media->getId()], $media->getContent());
            }
            return new LengthAwarePaginator($res, $matches->total(), $this->limit, $request->input('page') ?? 1);
        }
        return null;
    }
}
