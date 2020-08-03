<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

use Config;
use App\Video;
use App\QueryRule;
use App\CategoryRule;

class VideoController extends Controller
{
    /**
     * API Resource for Video Model
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Limit
        $limit = $request->has('limit') ? (int) $request->input('limit') : 50;

        // Sorting
        if ($request->has('sortby')) {
            switch ($request->input('sortby')) {
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
            $data = Video::where('id', $request->input('id'))->firstOrFail();

            // TODO: Transform data at insert.
            preg_match(config('regex.domain'), $data->embed, $url);
            $data->embed = $url[0];

            // Explode Categories
            $data->categories = explode(';', $data->categories);

            return $data;
        }

        // Collectio of models
        if ($request->has('collection')) {
            $collection = explode(',', $request->input('collection'));
            return Video::whereIn('id', $collection)->get();
        }

        // Category model listing
        if ($request->has('category')) {
            return Video::search($request->category)
                ->rule(CategoryRule::class)
                ->orderBy('views', 'DESC')
                ->paginate($limit);
        }

        // Search model listing
        if ($request->has('q') && !empty($request->input('q'))) {
            $data = Video::search($request->input('q'))->rule(QueryRule::class);
            $data->whereNotMatch('categories', config('const.excluded_cats'));

            if ($request->has('exclude')) {
                $data->whereNotIn('id', [$request->input('exclude')]);
            }

            if ($request->has('sortby')) {
                $data->orderBy($sort, 'DESC');
            }

            return $data->paginate($limit);
        }

        // Default model listing
        return Video::search('*')
            ->whereNotMatch('categories', config('const.excluded_cats'))
            ->orderby('views', 'desc')
            ->paginate($limit);
    }

    /**
     * Return top models by week
     */
    public function best()
    {
        return Video::search('*')
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
     * @return json
     */
    public function like($id)
    {
        if (Video::where('id', $id)->increment('likes')) {
            return response()->json(['success' => 1], 200);
        }
    }

    /**
     * Increment dislikes column on respective model
     *
     * @return json
     */
    public function dislike($id)
    {
        if (Video::where('id', $id)->increment('dislikes')) {
            return response()->json(['success' => 1], 200);
        }
    }
}
