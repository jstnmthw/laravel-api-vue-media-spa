<?php

namespace App\Console\Commands;

use App\Media;
use App\MySitemap;
use FilesystemIterator;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Sitemap;
use Exception;

class SitemapInsert extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:insert
                            { --ids= : ID\'s delimited by a comma }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert new records into an existing sitemap file or create a new file if over 10k';

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
     * @throws Exception
     */
    public function handle(): int
    {
        // Sitemap path default: sitemap/
        $path = public_path('sitemap/');

        // Create dir to store sitemap files
        if (!is_dir($path)) {
            mkdir($path);
        }

        // Check existing files and start count from last
        $fi = new FilesystemIterator($path, FilesystemIterator::SKIP_DOTS);
        $i = iterator_count($fi);

        // Get Ids passed from options
        $ids = explode(',', $this->option('ids'));

        // Get data
        $data = Media::query()
            ->whereIn('id', $ids)
            ->get();

        // File exists
        if ($i) {

            foreach ($data as $page) {

                // If current data is over the amount of max entries
                // Push the remaining amount of entries into file then
                // create a new file and push the rest.
                $file = public_path("sitemap/sitemap-{$i}.xml");
                $entries = (($this->getLines($file) - 3) / 6);
                $maxEntries = 10;

                if ($entries < $maxEntries) {

                    // not full
                    $this->info('Appending to file..');
                    $exec = exec('sed -i "$ d" ' . $file . ' && echo Done.');
                    if (!$exec) {
                        throw new Exception('Failed cutting the last line of the file.');
                    }

                    $sitemap = MySitemap::create()
                        ->add(url('/videos/'.$page->slug))
                        ->renderTags();

                    $contents = file_get_contents($file);
                    $contents .= $sitemap;
                    file_put_contents($file, $contents);

                } else {

                    // TODO: Code only adding one entry per file, the iterator $i needs to be refactored to some how loop the new file name.
                    // TODO: Meaning the iterator ($i) needs to increase after this else statement, refactor another iterator for the filename since it's in use.
                    $this->info('Creating new file..');
                    $i++;
                    MySitemap::create()
                        ->add($page->slug)
                        ->writeToFile(
                            public_path("sitemap/sitemap-{$i}.xml")
                        );

                }
            }

        // File doesn't exists
        } else {

            $this->info('File does not exist. Creating new file..');
            $sitemap = MySitemap::create();
            foreach ($data as $page) {
                    $sitemap->add($page->slug)
                        ->writeToFile(
                            public_path("sitemap/sitemap-1.xml")
                        );
            }

        }

        return 0;

    }

    /**
     * Count the number of lines in a text file
     * @param $file
     * @return int
     */
    private function getLines($file): int
    {
        $f = fopen($file, 'rb');
        $lines = 1;
        while (!feof($f)) {
            $lines += substr_count(fread($f, 8192), "\n");
        }
        fclose($f);
        return $lines;
    }

}
