<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use\Illuminate\Pagination\LengthAwarePaginator;

use App\Media;

class MediaController extends Controller
{
    private $limit;

    public function __construct(Request $request)
    {
        // Set default limit
        $this->limit = $this->page_limit($request);
    }

    /**
     * API Resource for Media Model
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function index(Request $request)
    {
        // Default documents listing
        $matches = Media::matchAllSearch()->size($this->limit)->execute();
        $docs = $matches->documents();
        $res = [];
        foreach ($docs as $media) {
            $res[] = array_merge(['id' => (int) $media->getId()], $media->getContent());
        }
        return new LengthAwarePaginator($res, $matches->total(), $this->limit, $request->input('page') ?? 1);
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
     * Get Media model by id via ElasticSearch
     * @param $id
     * @return Model|void
     */
    public function get($id) {
        $data = Media::search('*')->where('_id', $id)->first();
        return $data ? $data : abort(404);
    }

    /**
     * Return top models by week
     * @return LengthAwarePaginator
     */
    public function best()
    {
        return Media::search('*')
            ->where(
                'created_at',
                '>=',
                date('Y-m-d', time() - 7 * 24 * 60 * 60),
            )
            ->paginate(50);
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
}
