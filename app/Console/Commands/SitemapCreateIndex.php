<?php

namespace App\Console\Commands;

use FilesystemIterator;
use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapIndex;

class SitemapCreateIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a sitemap index';

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
        $this->comment('Creating sitemap index...');
        $path = public_path('sitemap');

        $fi = new FilesystemIterator($path);

        $siteIndex = SitemapIndex::create();

        foreach ($fi as $file) {
            $siteIndex->add('sitemap/' . $file->getFilename());
        }

        $siteIndex->writeToFile(public_path('sitemap.xml'));

        $this->info('Done.');

        return 0;
    }
}
