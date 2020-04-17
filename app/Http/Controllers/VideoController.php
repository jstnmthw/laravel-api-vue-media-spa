<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Http\Resources\DataCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use App\VideoData;
use App\VideoCategories;

class VideoController extends Controller
{
    /**
     * Display & paginate a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cat = false;
        $page = is_numeric($request->input('page')) ? (int) $request->input('page') : 1;
        $limit = 40;
        $offset = ($page - 1) * 40 + 1;

        if(!NULL == $request->input('category')) {
            $cat = $this->escape_like(str_replace('-', ' ', $request->input('category')));
        }

        // dd($cat);

        $seek = VideoData::select('id', 'views')
                    ->when($cat, function ($query, $cat) {
                        return $query->where('categories', 'like', '%'.$cat.'%');
                    })
                    ->offset($offset)
                    ->orderBy('views', 'DESC')
                    ->limit($limit)
                    ->get();

        if(empty($seek)) {
            return ['error' => 'No rows found.'];
        }

        // dd($seek);

        foreach($seek as $row) {
            $ids[] = $row['id'];
        }

        $total = Cache::remember('videos_total', 1500, function () {
            return DB::select('SELECT COUNT(views) as count FROM video_data');
        });

        $data['data'] = VideoData::find($ids);
        $data['current_page'] = $page;
        $data['total'] = $total[0]->count;
        $data['last_page'] = ceil($total[0]->count / $limit);
        $data['per_page'] = $limit;

        return $data;
    }

    /**
     * Show videos associated with their categories.
     * 
     * @return \Illuminate\Http\Response
     */
    public function categories($cat)
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 0;
        $limit = 40;
        $offset = ($page - 1) * 40 + 1;
        $cat = $this->escape_like($cat);

        $total = Cache::remember($cat.'_total', 500, function () use ($cat) {
            return DB::select('SELECT count(views) as count FROM video_data WHERE categories LIKE "%'.$cat.'%"');
        });

        // if($total) {
        //    return json_encode(['error' => 'No results.']);
        // }

        $data = new DataCollection(
            VideoData::where('categories', 'like', '%'.$cat.'%')
                ->orderby('views', 'desc')
                ->offset($offset)
                ->limit($limit)
                ->get()
        );
    
        $paginate = new LengthAwarePaginator($data, $total[0]->count, $limit, $page);
    
        return $paginate;
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
        return Data::where('id', $id)->firstOrFail();
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
     * Escape special characters for a LIKE query.
     *
     * @param string $value
     * @param string $char
     *
     * @return string
     */
    private function escape_like(string $value, string $char = '\\'): string
    {
        return str_replace(
            [$char, '%', '_'],
            [$char.$char, $char.'%', $char.'_'],
            $value
        );
    }


    



    /**
     * This is not recommended.
     */
    public function update_db() {

        $count = 0;
        VideoData::orderBy('id')->chunk(1000, function ($data) use ($count) {
            foreach ($data as $key => $value) {
                $cats = explode(';', $value['categories']);
                foreach ($cats as $cat_key => $cat_value) {
                    VideoCategories::insert(['cat_id' => $cat_key + 1, 'video_data_id' => $value['id']]);
                    // echo 'Saved "' . $cat_value . '" to ID #' . $value['id'] . '<br>';
                }
            }
            $count++;
            echo 'Chunk #' . $count;
        });


        // $data = VideoData::limit(5)->get()->toArray();

        // dd($data);

        // foreach ($data as $key => $value) {

        //     $cats = explode(';', $value['categories']);

        //     foreach ($cats as $cat_key => $cat_value) {


        //         // dd($key);
        //         VideoCategories::insert(['cat_id' => $cat_key + 1, 'video_data_id' => $value['id']]);
        //         // $key->categories()->save($value);
        //         echo 'Saved.';

        //     }
            
        // }

    }




}
