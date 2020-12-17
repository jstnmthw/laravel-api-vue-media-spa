<?php

namespace App\Console\Commands;

use App\Category;
use Illuminate\Console\Command;
use App\Media;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Sitemap\Sitemap;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new sitemap from database';

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
     * @return int
     */
    public function handle()
    {
        $sitemap = Sitemap::create();
        $bar = $this->output->createProgressBar(Media::count());
        $bar->start();
        $this->newLine();
        $this->comment('Generating App\Media entries...');
        DB::table('media')->orderBy('id')->chunk(500, function ($media) use ($sitemap, $bar) {
            foreach ($media as $row) {
                $slug = Str::slug(strtolower($row->title));
                $sitemap->add(url("/media/{$slug}"));
            }
            $bar->advance();
        });
        $bar->finish();
        $this->newLine();
        $this->info('Finished.');
        $this->comment('Generating App\Categories entries...');
        $bar = $this->output->createProgressBar(Category::count());
        $bar->start();
        DB::table('categories')->orderBy('id')->chunk(500, function ($categories) use ($sitemap, $bar) {
            foreach ($categories as $row) {
                $slug = Str::slug(strtolower($row->name));
                $sitemap->add(url("/categories/{$slug}"));
            }
            $bar->advance();
        });
        $sitemap->writeToFile(public_path('sitemap.xml'));
        $bar->finish();
        $this->newLine();
        $this->info('Sitemap is complete!');
        return 1;
    }
}
