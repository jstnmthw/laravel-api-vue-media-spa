<?php

namespace App\Console\Commands;

use App\Category;
use Illuminate\Console\Command;
use App\Media;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Symfony\Component\Console\Input\InputArgument;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:sitemap {chunkSize=500}';

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
    public function handle(): int
    {
        $i = 0;
        $chunk = $this->argument('chunkSize');
        DB::table('media')->orderBy('id')->chunk($chunk, function ($media) use (&$i) {
            $this->comment("Creating sitemap-{$i}.xml...");
            $bar = $this->output->createProgressBar($media->count());
            $bar->start();
            $sitemap = Sitemap::create();
            foreach ($media as $row) {
                $slug = Str::slug(strtolower($row->title));
                $sitemap->add(url("/media/{$slug}"));
                $bar->advance();
            }
            is_dir(public_path('sitemap')) ?: mkdir(public_path('sitemap'));
            $sitemap->writeToFile(public_path("sitemap/sitemap-{$i}.xml"));
            $i++;
            $bar->finish();
            $this->newLine();
        });
        $this->info('Done.');
        $this->newLine();
        $this->comment('Creating sitemap index...');
        $siteIndex = SitemapIndex::create();
        for ($x = 0; $x < $i; $x++) {
            $siteIndex->add(public_path("sitemap/sitemap-{$x}.xml"));
        }
        $siteIndex->writeToFile(public_path('sitemap.xml'));
        $this->info('Done.');
        return 1;
    }
}
