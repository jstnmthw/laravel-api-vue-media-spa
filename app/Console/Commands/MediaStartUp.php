<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class MediaStartUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start the first initial pull and import of the app.';

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
        $fn = config('const.media_new_fn');
        $csv = config('const.media_new_unzipped');

        $fp = storage_path('app/' . $fn);
        $fp_csv = storage_path('app/' . $csv);

        /* Download */
//        Log::info('Downloading file.');
//        Artisan::call('file:download', [
//            'url' => config('const.media_csv_url'),
//            'filename' => $fn,
//        ]);
//        Log::info('Finished downloading file.');

        /* Unzip */
//        Log::info('Unzipping file.');
//        Artisan::call('file:unzip', [
//            'file' => $fp
//        ]);
//        Log::info('Finished unzipping file.');

        /* Split */
//        Log::info('Splitting file.');
//        Artisan::call('file:split', [
//            'file' => $fp_csv,
//            'output' => '/tmp/SQL/media/',
//            '--lines' => 500000
//        ]);
//        Log::info('Slitting file.');

        /* Import  */
        Log::info('Importing files.');
        Artisan::call('media:csv:batch-import', [
            '--path' => '/tmp/SQL/media/',
        ]);
        Log::info('Finished importing files.');

        Log::info('Importing to Elastic.');
        Artisan::call('scout:import App\\Media');
        Log::info('Finished importing to Elastic.');

        return 0;
    }
}
