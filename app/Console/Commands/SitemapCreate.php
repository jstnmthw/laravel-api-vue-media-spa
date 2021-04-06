<?php

namespace App\Console\Commands;

use FilesystemIterator;
use Illuminate\Console\Command;
use App\Media;
use Illuminate\Support\Str;
use Spatie\Sitemap\Sitemap;

class SitemapCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:create {from=0} {size=500} {chunk=250}';

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
        // Sitemap path default: sitemap/
        $path = public_path('sitemap');

        // Create dir to store sitemap files
        if (!is_dir($path)) {
            mkdir($path);
        }

        // Check existing files and start count from last
        $fi = new FilesystemIterator($path);
        $i = iterator_count($fi);

        // Start, finish, chunk arguments
        $from = $this->argument('from');
        $size = $this->argument('size');
        $chunk = $this->argument('chunk');

        // Get chunk data
        $data = Media::query()
            ->orderBy('id')
            ->where('id', '>', $from)
            ->where('id', '<=', $from + $size);

        // Chunk through data
        $data->chunk($chunk, function ($media) use (&$i) {

            // Initiate sitemap
            $sitemap = Sitemap::create();

            // Output
            $this->comment("Creating sitemap-{$i}.xml...");

            // Console bar
            $bar = $this->output->createProgressBar($media->count());
            $bar->start();

            // Add url for each record
            foreach ($media as $row) {

                // Slug the url
                $slug = Str::slug(strtolower($row->title));

                // Add url
                $sitemap->add(url("/media/{$slug}"));

                // Advance console bar
                $bar->advance();

            }

            // Create sitemap file
            $file = fopen(public_path("sitemap/sitemap-{$i}.xml"), 'w');

            // Write file
            fwrite($file, $sitemap->render());

            // Free memory
            unset($sitemap);

            // Chunk finished, increase by 1
            $i++;

            // Console Bar
            $bar->finish();

            // Console newline
            $this->newLine();
        });

        // Console output
        $this->info('Done.');

        // Successfully run
        return 0;
    }
}
