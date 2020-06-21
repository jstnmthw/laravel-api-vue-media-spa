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

        // Model category listing
        if ($request->has('category')) {
            return Video::search($request->category)
                ->rule(CategoryRule::class)
                ->orderBy('views', 'DESC')
                ->paginate($limit);
        }

        // Search model listing
        if ($request->has('query')) {
            $data = Video::search($request->input('query'));
            $data->whereNotMatch('categories', config('const.excluded_cats'));

            if ($request->has('exclude')) {
                $data->whereNotIn('id', [$request->input('exclude')]);
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
     * Select Video model and related models
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        // Get video with categories
        $data = Video::where('videos.id', $id)
            ->with('categories:categories.id,name')
            ->first();

        // TODO: Transform data at insert.
        preg_match(config('regex.domain'), $data->embed, $url);
        $data->embed = $url[0];

        return $data;
    }
}
