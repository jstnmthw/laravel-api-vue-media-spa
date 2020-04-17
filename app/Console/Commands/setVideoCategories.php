<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class setVideoCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:video_categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Chunk through a large denormalized dataset and parse/insert categories to a seperate table.';

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
        //
    }
}
