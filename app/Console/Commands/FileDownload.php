<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;

class FileDownload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:download
                           { url : Remote file url. }
                           { filename : Name of the locally downloaded file. }
                           { --timeout=3600 : Set timeout before canceling. }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download file from a remote server to the default storage directory';

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
        set_time_limit(0);
        $fn = storage_path('app/' . $this->argument('filename'));
        $ch = curl_init();
        $fp = fopen($fn, 'w');
        curl_setopt_array($ch, array(
            CURLOPT_URL     => $this->argument('url'),
            CURLOPT_TIMEOUT => $this->option('timeout'),
            CURLOPT_FILE    => $fp,
            CURLOPT_PORT    => 443
        ));
        $content = curl_exec($ch);
        if (empty($content)) {
          unlink($fn);
        }
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }
        curl_close($ch);
        fclose($fp);
        return 0;
    }
}
