<?php

namespace App\Console\Commands;

use App\Media;
use App\MediaDeleted;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MediaPurge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:purge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download, cut, copy, import and compare deleted data to current and remove deleted found.';

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
        Log::info('Starting media:purge');

        $fn = 'media_deleted.csv';
        $fn_cut = 'media_deleted-cut.csv';

        $fp = storage_path('app/' . $fn);
        $fp_cut = storage_path('app/' . $fn_cut);

        Artisan::call('file:download', [
            'url' => config('const.media_deleted_csv_url'),
            'filename' => $fn,
        ]);

        Artisan::call('file:cut', [
            'file' => $fp,
            'output' => $fp_cut,
            '--lines' => 100,
        ]);

        Artisan::call('file:copy', [
            'file' => $fp_cut,
            'output' => '/tmp/SQL',
        ]);

        DB::table('media_deleted')->truncate();

        Artisan::call('media:csv:import-deleted', [
            'file' => $fn_cut,
        ]);

        MediaDeleted::query()->chunkById(500, function($chunk) {
            $ids = $chunk->pluck('unique_key');
            $query = Media::query()->whereIn('unique_key', $ids);
            if ($query->exists()) {
                $query->delete();
            }
        });

        Log::info('Successfully ran media:purge');

        return 0;
    }

}
