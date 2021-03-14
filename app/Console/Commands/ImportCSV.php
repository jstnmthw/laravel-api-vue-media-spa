<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:import
                            { filename : CSV filename ex: media.csv }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import a specifically structured CSV file into media table.';

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
            'LOAD DATA INFILE "/tmp/SQL/'.$this->argument('filename').'" IGNORE
            INTO TABLE media
            FIELDS TERMINATED BY "|" ESCAPED BY ""
            LINES TERMINATED BY "\n"
            (@url, thumbnail, album, title, @tags, categories, @author, duration, views, @likes, @dislikes, @dummy, @dummy)
            SET author = IF(@author = "", NULL, @author),
                url = SUBSTRING(@url,14,45),
                external_key = SUBSTRING(@url,44,15),
                likes = IF(@likes = "", 0, @likes),
                dislikes = IF(@dislikes = "", 0, @dislikes),
                created_at = CURRENT_TIMESTAMP,
                updated_at = CURRENT_TIMESTAMP;';

        DB::connection()->getpdo()->exec($query);

        $this->info('Done.');
        return true;
    }
}
