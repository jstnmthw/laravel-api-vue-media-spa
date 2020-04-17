<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use App\Categories;
use App\VideoData;
use App\VideoCategories;

class setVideoCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'video:categorize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Chunk through a large denormalized video csv dataset and parse/insert categories to a seperate table.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        if ($this->confirm('Are you sure you want to continue?')) {

            $categories = Categories::all()->toArray();

            $total = DB::select('SELECT count(views) as count FROM video_data');

            $progressBar = $this->output->createProgressBar($total[0]->count);
            $progressBar->start();
            // update every 100 iterations
            $progressBar->setRedrawFrequency(100);

            VideoData::orderBy('id')->chunk(500, function ($data) use ($progressBar, $categories) {

                foreach ($data as $key => $value) {
            
                    $cats = explode(';', $value['categories']);

                    foreach ($cats as $cat_key => $cat_value) {

                        if ($cat = array_search($cat_value, array_column($categories, 'name'))){

                            VideoCategories::insert(['cat_id' => $cat + 1, 'video_data_id' => $value['id']]);

                        }

                    }
                    
                    $progressBar->advance();

                }

            });

            $progressBar->finish();

        }
    }

}
