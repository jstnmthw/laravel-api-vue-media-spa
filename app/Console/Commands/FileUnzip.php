<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;

class FileUnzip extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:unzip {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unzip a zip file.';

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
        $fp = $this->argument('file');
        $path = storage_path('app');
        $exec = exec('unzip -o '.$fp. ' -d '.$path.' && echo Done.');
        if(!$exec) {
            throw new Exception('Failed unzipping file.');
        }
        return 0;
    }
}
