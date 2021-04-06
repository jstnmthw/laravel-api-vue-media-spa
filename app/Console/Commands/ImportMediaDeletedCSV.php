<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportMediaDeletedCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:csv:import-deleted
                            { file : CSV filename ex: media.csv }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import a specifically structured CSV file into media deleted table';

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
        $this->comment('Starting CSV import to MySQL...');
        $query =
            'LOAD DATA INFILE "/tmp/SQL/'.$this->argument('file').'" IGNORE
            INTO TABLE media_deleted
            CHARACTER SET utf8mb4
            FIELDS TERMINATED BY "," ESCAPED BY ""
            LINES TERMINATED BY "\n"
            (@int, @unique_key, @url)
            SET unique_key = @unique_key';

        DB::connection()->getpdo()->exec($query);

        $this->info('Done.');
        return 0;
    }
}
