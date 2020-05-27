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


class VideoController extends Controller
{

    /**
     * Display & paginate a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        /**
         * Queries become slow and unresponsive when using offsets on large datasets. 
         * Reduce the first select to indexed only columns. A subsequent query to collect 
         * all the columns by ID's collected. This reduces the query time significantly.
         * 
         * This is called the seek method.
         * For more info: https://use-the-index-luke.com/sql/partial-results/fetch-next-page
         */
        $cat        = $this->getCategory($request->input('category'));
        $page       = is_numeric($request->input('page')) ? (int) $request->input('page') : 1;
        $limit      = is_numeric($request->input('limit')) && (int) $request->input('limit') < 50 ? (int) $request->input('limit') : 50;
        $offset     = $page > 1 ? ($page - 1) * $limit + 1 : 0;

        // Check for sortby request
        switch ($request->input('sortby')) {
            case 'most_views':
                $sortby = 'views';
                break;
            case 'top_rated':
                $sortby = 'likes';
                break;
            case 'duration':
                $sortby = 'duration';
                break;
            case 'most_recent':
                $sortby = 'created_at';
                break;
            default:
                $sortby = false;
            break;
        }

        // Run seek query by ids (and sort if present).
        // $seek = Cache::remember('videos_'.($cat ? $cat.'_' : '').$sortby.'_page_'.$page, 33000, 
        //             function () use ($cat, $sortby, $limit, $offset) {
        //                 return Video::select('videos.id')
        //                     ->when($cat, function ($query, $cat) {
        //                         return $query->whereHas('categories', function (Builder $query) use ($cat) {
        //                             return $query->where('categories.id', $cat);
        //                         });
        //                     })
        //                     ->when(!$cat, function ($query) {
        //                         return $query->whereDoesntHave('categories', function (Builder $query) {
        //                             return $query->whereIn('categories.id', config('const.excempt'));
        //                         });
        //                     })
        //                     ->when($sortby, function ($query) use ($sortby) {
        //                         return $query->orderBy($sortby, 'DESC');
        //                     })
        //                     ->limit($limit)
        //                     ->offset($offset)
        //                     ->get();
        //             }
        //         );

        // Temp cache disable
        // return $query->where('categories.id', '!=', config('const.excempt'));
        // whereHas('categories', function (Builder $query) use ($cat) {
        //   return $query->where('categories.id', $cat);
        //})
        $seek =  
        Video::select('videos.id')
            ->when($cat, function ($query, $cat) {
                return $query->whereHas('categories', function (Builder $query) use ($cat) {
                    return $query->where('categories.id', $cat);
                });
            })
            ->when(!$cat, function ($query) {
                return $query->whereDoesntHave('categories', function (Builder $query) {
                    return $query->whereIn('categories.id', config('const.excempt'));
                });
            })
            ->when($sortby, function ($query) use ($sortby) {
                return $query->orderBy($sortby, 'DESC');
            })
            ->limit($limit)
            ->offset($offset)
            ->get();
        
        // Return error if no results.
        if(empty($seek->toArray())) {
            return ['error'=> 'No videos found in this category.'];
        }

        // Push collect of IDs to array.
        foreach($seek as $row) {
            $ids[] = $row['id'];
        }

        /**
         * Normally, large dataset pagination only include next/prev (Laravel's SimplePaginate). 
         * In order to show previous, next and last pages, we must query the database to count 
         * the amount of records. The data should already be indexed but we will cache the first 
         * results to make this virtually queryless.
         */
        if(!$cat) {
            $total = Cache::remember('videos_total', 33100, function () {
                return Video::count();
            });
        } else {
            $total = Cache::remember('cat'.$cat.'_total', 33100, function () use ($cat) {
                return Category::find($cat)->videos()->count();
            });
        }

        // Now, collect all the information from ids selected (fast).
        $data['data'] = Video::whereIn('id', $ids)
                            ->when($sortby, function ($query, $sortby) {
                                return $query->orderBy($sortby, 'DESC');
                            })
                            ->get();

        foreach ($data['data'] as $row => $value) {
            $value['album'] = explode(';',$value['album']);
        }

        // Manually set json paginate for front end.
        $data['total'] = $total;
        $data['per_page'] = $limit;
        $data['last_page'] = ceil($total / $limit);
        $data['current_page'] = $page;

        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {

        // Grab related videos
        if($request->has('related')) {
            /**
             * TODO: We need a better way of showing related videos. Possibily a full text search
             * on titles instead of picking one category out of the bunch.
             */
            $cat_id = $_COOKIE['category'] != null ? $this->getCategory($_COOKIE['category']) : $this->getCategory($request->input('related'));
            $related = Video::whereHas('categories', function (Builder $query) use ($cat_id) {
                $query->where('categories.id', $cat_id)
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
        $data = Video::where('videos.id', $id)->with('categories:categories.id,name')->first();

        // TODO: Replace at database insert
        preg_match(config('regex.domain'), $data->embed, $url);
        $data->embed = $url[0];

        return $data;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    /**
     * Match Category param with database and return category ID.
     * 
     * @param string $cat
     * 
     * @return integer
     * @return false
     */
    private function getCategory($cat = null) 
    {

        if(null !== $cat) {

            $cat = strtolower(str_replace('-', ' ', $cat));
            $db_cats = Category::select('id', 'name')->get()->toArray();
            $match = array_search($cat, array_map('strtolower', array_column($db_cats, 'name')));
            
            return $match !== false ? $db_cats[$match]['id'] : abort(404);

        }

        return false;

    }

}
