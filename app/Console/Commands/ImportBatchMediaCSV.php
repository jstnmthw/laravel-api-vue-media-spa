<?php

namespace App\Console\Commands;

use Doctrine\Foo\Bar;
use FilesystemIterator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class ImportBatchMediaCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:csv:batch-import
                            { --path= : Directory where files are located }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import multiple CSV files into Media table.';

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
        $path = $this->option('path');
        $fi = new FilesystemIterator($path);
        $fc = iterator_count($fi);
        $this->comment('Importing ' .$fc. ' CSV files into Media database...');
        Log::info('Importing ' .$fc. ' CSV files into Media database...');
        $bar = $this->output->createProgressBar($fc);
        $bar->start();
        foreach ($fi as $file) {
            Artisan::call('media:csv:import', [
                'table' => 'media',
                'file' => $file->getPathname(),
            ]);
            $bar->advance();
            Log::info('Imported ' . $file->getPathname());
        }
        $bar->finish();
        $this->newLine();
        $this->info('Done.');
        return 0;
    }
}
