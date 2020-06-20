<?php

namespace App\Http\Controllers;

use Config;
use DB;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

use App\Video;
use App\Categorizable;
use App\Category;

use App\CategoryRule;

class VideoController extends Controller
{
    /**
     * Display & paginate a listing of the resource.
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

        return Video::select('videos.id')
            ->orderBy('views')
            ->paginate(50);
    }
}
