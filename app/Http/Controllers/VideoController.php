<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

use Config;
use App\Video;
use App\CategoryRule;
use App\DefaultRule;

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

        // Specific model by ID
        if ($request->has('id')) {
            $data = Video::where('id', $request->input('id'))->firstOrFail();

            // TODO: Transform data at insert.
            preg_match(config('regex.domain'), $data->embed, $url);
            $data->embed = $url[0];

            return $data;
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
            $data = Video::search($request->input('q'));
            $data->whereNotMatch('categories', config('const.excluded_cats'));

            if ($request->has('exclude')) {
                $data->whereNotIn('id', [$request->input('exclude')]);
            }

            if ($sort) {
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
}
