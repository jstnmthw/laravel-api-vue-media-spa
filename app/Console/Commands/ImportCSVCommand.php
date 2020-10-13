<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportCSVCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:csv {filepath}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import your CSV file into the database.';

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
    public function handle()
    {
        return 0;
    }
}
