<?php

namespace App\Console\Commands;

use FilesystemIterator;
use Illuminate\Console\Command;
use App\Media;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Sitemap\Sitemap;

class SitemapCompress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:compress {--D|--delete}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compress your sitemap file(s)';

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
     * GZIPs a file on disk (appending .gz to the name)
     *
     * From http://stackoverflow.com/questions/6073397/how-do-you-create-a-gz-file-using-php
     * Based on function by Kioob at:
     * http://www.php.net/manual/en/function.gzwrite.php#34955
     *
     * @param string $source Path to file that should be compressed
     * @param integer $level GZIP compression level (default: 9)
     * @return string New filename (with .gz appended) if success, or false if operation fails
     */
    private function gzCompressFile(string $source, $level = 9){
        $dest = $source . '.gz';
        $mode = 'wb' . $level;
        $error = false;
        if ($fp_out = gzopen($dest, $mode)) {
            if ($fp_in = fopen($source,'rb')) {
                while (!feof($fp_in))
                    gzwrite($fp_out, fread($fp_in, 1024 * 512));
                fclose($fp_in);
            } else {
                $error = true;
            }
            gzclose($fp_out);
        } else {
            $error = true;
        }
        if ($error) {
            $this->error("Error: There was an error compressing the file.");
            return 0;
        }
        else
            return $dest;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        // Console comment
        $this->comment("Compressing sitemaps...");

        // Sitemap path default: sitemap/
        $path = public_path('sitemap');

        // Check directory exists
        if (!is_dir($path)) {
            $this->error("Error: Directory does not exist.");
            return 0;
        }

        // Initiate FilesystemIterator
        $fi = new FilesystemIterator($path);

        // Collect only xml files
        $files = [];
        foreach ($fi as $file) {
            if($file->getExtension() === 'xml')
                $files[] = $file->getRealPath();
        }

        // Console bar
        $bar = $this->output->createProgressBar(count($files));
        $bar->start();

        // Compress each xml file
        foreach ($files as $file) {
            $bar->advance();
            $this->gzCompressFile($file);
            if($this->option('delete'))
                Storage::delete($file);
        }

        // Console bar
        $bar->finish();

        // Console comment
        $this->newLine();
        $this->info("Done.");

        // Successfully run
        return 1;
    }
}
