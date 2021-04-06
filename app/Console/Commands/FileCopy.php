<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;

class FileCopy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:copy
                            { file : File location },
                            { output : File output location }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy file into another location';

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
        $output = $this->argument('output');
        $exec = exec('cp -f '.$fp.' ' .$output . '&& echo Done.');
        if(!$exec) {
            throw new Exception('Failed copying file to directory.');
        }
        return 0;
    }
}
