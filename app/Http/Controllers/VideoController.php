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
        if ($request->has('category')) {
            return Video::search($request->category)
                ->rule(CategoryRule::class)
                ->orderBy('views', 'DESC')
                ->paginate(50);
        }

        if ($request->has('search')) {
            return Video::search($request->search)->paginate(50);
        }

        return Video::search('*')
            ->whereNotMatch('categories', config('const.excluded_cats'))
            ->orderby('views', 'desc')
            ->paginate(50);
    }

    /**
     * Select Video model and related models
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        // Grab related videos
        if ($request->has('related')) {
            /**
             * TODO: We need a better way of showing related videos. Possibily a full text search
             * on titles instead of picking one category out of the bunch.
             */
            $cat_id =
                $_COOKIE['category'] != null
                    ? $this->getCategory($_COOKIE['category'])
                    : $this->getCategory($request->input('related'));

            $related = Video::whereHas('categories', function (
                Builder $query
            ) use ($cat_id) {
                $query
                    ->where('categories.id', $cat_id)
                    ->where('categories.id', '!=', 43); // TODO Need to add check for excemptions
            })
                ->where('id', '!=', $id)
                ->where('views', '>', 10000)
                ->inRandomOrder()
                ->limit(12)
                ->get();

            return $related;
        }

        // Get video with categories
        $data = Video::where('videos.id', $id)
            ->with('categories:categories.id,name')
            ->first();

        // TODO: Replace at database insert
        preg_match(config('regex.domain'), $data->embed, $url);
        $data->embed = $url[0];

        return $data;
    }
}
