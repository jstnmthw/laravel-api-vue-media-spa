<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SitemapReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes your sitemap folder.';

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
        // Sitemap directory
        $directory = public_path('sitemap');

        // Confirmation text
        $q = 'This will delete all your sitemaps inside the folder. Do you wish to continue?';

        if ($this->confirm($q)) {
            Storage::deleteDirectory($directory);
        }

        // Successfully run
        return 1;
    }
}
