<?php

namespace App\Http\Controllers;

use Config;
use DB;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

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
        $limit      = 40;
        $offset     = $page > 1 ? ($page - 1) * 40 + 1 : 0;

        // Default set to false
        $views      = false;
        $likes      = false;
        $duration   = false;
        $recent     = false;

        // Check for sort request
        if($request->has('sortby')) {
            switch ($request->input('sortby')) {
                case 'most_views':
                    $views = true;
                    break;
                case 'top_rated':
                    $likes = true;
                    break;
                case 'duration':
                    $duration = true;
                    break;
                case 'most_recent':
                    $recent = true;
                    break;
            }
        }

        // Run seek query by ids (and sort if present).
        $seek = Video::select('videos.id')
            ->when($views, function ($query) {
                return $query->addSelect('views');
            })
            ->when($duration, function ($query) {
                return $query->addSelect('duration');
            })
            ->when($likes, function ($query) {
                return $query->addSelect('likes');
            })
            ->when($cat, function ($query, $cat) {
                return $query->join('categorizables', 'videos.id', '=' ,'categorizables.categorizable_id')
                    ->where('categorizables.category_id', $cat);
            })
            ->offset($offset)
            ->when($views, function ($query) {
                return $query->orderBy('views', 'DESC');
            })
            ->when($duration, function ($query) {
                return $query->orderBy('duration', 'DESC');
            })
            ->when($likes, function ($query) {
                return $query->orderBy('likes', 'DESC');
            })
            ->limit($limit)
            ->get();

        // Return error is no results.
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
            $total = Cache::remember('videos_total', 3100, function () {
                return Video::count();
            });
        } else {
            $total = Cache::remember('cat'.$cat.'_total', 300, function () use ($cat) {
                return Category::find($cat)->videos()->count();
            });
        }

        // dd(Category::find(1)->videos()->count());

        // Now, collect all the information from ids selected (fast).
        $data['data'] = Video::whereIn('id', $ids)
            ->when($views, function ($query) {
                return $query->orderBy('views', 'DESC');
            })
            ->when($duration, function ($query) {
                return $query->orderBy('duration', 'DESC');
            })
            ->get();

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
    public function show($id)
    {

        // $data = Video::with('categories')->find($id);
        $data = Video::where('id', $id)->with('categories:id,name')->first();
        
        // Grab the next 10 relevant videos in this category
        $related = Category::where('id', 1)
            ->with(['videos' => function($query){ 
                return $query->take(10); 
            }])
            ->first();

        // Add relevant videos to collection
        $data->related = $related->videos;

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
    private function getCategory($cat = null) {

        if(null !== $cat) {

            $cat = strtolower(str_replace('-', ' ', $cat));
            $db_cats = Category::select('id', 'name')->get()->toArray();
            $match = array_search($cat, array_map('strtolower', array_column($db_cats, 'name')));
            
            return $match !== false ? $db_cats[$match]['id'] : abort(404);

        }

        return false;

    }

}
