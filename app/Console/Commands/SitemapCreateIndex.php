<?php

namespace App\Console\Commands;

use App\Category;
use FilesystemIterator;
use Illuminate\Console\Command;
use App\Media;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use stdClass;
use Symfony\Component\Console\Input\InputArgument;

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
        $path = public_path('sitemap');

        $fi = new FilesystemIterator($path);
        $i = iterator_count($fi);

        $siteIndex = SitemapIndex::create();
        for ($x = 0; $x < $i; $x++) {
            $siteIndex->add($path . "sitemap-{$x}.xml");
        }
        $siteIndex->writeToFile($path .'sitemap.xml');

        $this->info('Done.');

        return 1;
    }
}
