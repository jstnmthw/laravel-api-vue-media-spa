<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use Config;
use App\Media;
use App\QueryRule;
use App\CategoryRule;

class VideoController extends Controller
{
    /**
     * API Resource for Media Model
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function index(Request $request)
    {
        // Limit
        $limit = $request->has('limit') ? (int) $request->input('limit') : 50;

        // Sorting
        if ($request->has('sort_by')) {
            switch ($request->input('sort_by')) {
                case 'most_views':
                    $sort = 'views';
                    break;
                case 'duration':
                    $sort = 'duration';
                    break;
                case 'most_recent':
                    $sort = 'created_at';
                    break;
            }
        }

        // Model by ID
        if ($request->has('id')) {
            $data = Media::where('id', $request->input('id'))->firstOrFail();

            // TODO: Transform data at insert.
            preg_match(config('regex.domain'), $data->embed, $url);
            $data->embed = $url[0];

            // Explode Categories
            $data->categories = explode(';', $data->categories);

            return $data;
        }

        // Collection of models
        if ($request->has('collection')) {
            $collection = explode(',', $request->input('collection'));
            return Media::whereIn('id', $collection)->get();
        }

        // Category model listing
        if ($request->has('category')) {
            return Media::search($request->input('category'))
                ->rule(CategoryRule::class)
                ->orderBy('views', 'DESC')
                ->paginate($limit);
        }

        // Search model listing
        if ($request->has('q') && !empty($request->input('q'))) {
            $data = Media::search($request->input('q'))->rule(QueryRule::class);
            $data->whereNotMatch('categories', config('const.excluded_cats'));

            if ($request->has('exclude')) {
                $data->whereNotIn('id', [$request->input('exclude')]);
            }

            if ($request->has('sortby')) {
                $data->orderBy($sort, 'DESC');
            }

            return $data->paginate($limit);
        }

        // Most Views
        if ($request->has('most_viewed')) {
            return Media::search('*')
                ->orderBy('views', 'desc')
                ->paginate($limit);
        }

        // Default model listing
        return Media::search('*')
            ->whereNotMatch('categories', config('const.excluded_cats'))
            ->orderby('views', 'desc')
            ->paginate($limit);
    }

    /**
     * Return top models by week
     *
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
     * Increment likes column on respective model
     *
     * @return JsonResponse
     */
    public function like($id)
    {
        if (Media::where('id', $id)->increment('likes')) {
            return response()->json(['success' => 1], 200);
        }
    }

    /**
     * Increment dislikes column on respective model
     *
     * @return JsonResponse
     * @mixin Builder
     */
    public function dislike($id)
    {
        if (Media::where('id', $id)->increment('dislikes')) {
            return response()->json(['success' => 1], 200);
        }
    }
}
