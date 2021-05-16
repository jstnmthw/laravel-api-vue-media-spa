<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;

class FileCut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:cut
                            { file : Filename },
                            { output : File output name },
                            { --lines=500 : Number of line starting from the end of the file }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cut the end of the file by nth line(s).';

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
        $lines = $this->option('lines') ?? 1000;
        $output = $this->argument('output');
        if (file_exists($output)) {
            unlink($output);
        }
        $exec = exec('tail '.$fp.' -n '.$lines.' >> '.$output . '&& echo Done.');
        if(!$exec) {
            throw new Exception('Failed executing tail command.');
        }
        return 0;
    }
}
