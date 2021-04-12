<?php

namespace App\Console\Commands;

use App\Media;
use App\MediaNew;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MediaAddNew extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:add-new';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download, unzip, cut, copy latest media csv from remote url';

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
        Log::info('Starting media:add-new');
        $this->comment('Downloading and adding new media...');

        $fn = config('const.media_new_fn');
        $fn_cut = 'media-new.csv';
        $fn_unzip = config('const.media_new_unzipped');

        $fp = storage_path('app/' . $fn);
        $fp_cut = storage_path('app/' . $fn_cut);
        $fp_unzip = storage_path('app/' . $fn_unzip);

        Artisan::call('file:download', [
            'url' => config('const.media_csv_url'),
            'filename' => $fn,
        ]);

        Artisan::call('file:unzip', [
            'file' => $fp
        ]);

        Artisan::call('file:cut', [
            'file' => $fp_unzip,
            'output' => $fp_cut,
            '--lines' => 10000,
        ]);

        Artisan::call('file:copy', [
            'file' => $fp_cut,
            'output' => '/tmp/SQL',
        ]);

        DB::table('media_new')->truncate();

        Artisan::call('media:csv:import', [
            'table' => 'media_new',
            'file' => $fn_cut,
        ]);

        MediaNew::query()->chunkById(100, function($chunk) {
            $newIds = [];
            foreach ($chunk as $row) {
                if (!Media::query()->where('unique_key', $row->unique_key)->exists()) {
                    $clone = $row->toArray();
                    unset(
                        $clone['id'],
                        $clone['deleted_at'],
                    );
                    $clone['created_at'] = now();
                    $clone['updated_at'] = now();
                    $newIds[] = Media::query()->create($clone)->id;
                }
            }
            Artisan::call('sitemap:insert', [
                '--ids' => implode(',', $newIds)
            ]);
        });

        Log::info('Successfully ran media:add-new');
        return 0;
    }

}
