<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;

class FileSplit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:split
                            { file : Filename },
                            { --lines=500 : Split file by number of lines }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Split a file by number of lines into the split directory';

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
        $file = $this->getFileInfo($this->argument('file'));
        $lines = $this->option('lines');

        if (file_exists($file['split_path'])) {
            rmdir($file['split_path']);
        }
        mkdir($file['split_path']);

        $response = null;
        $return = null;
        exec('split -dl '.$lines.' --additional-suffix=.'.$file['ext'] . ' ' . $file['file'] . ' ' . $file['out'], $response, $return);

        if ($return !== 0) {
            throw new Exception('Failed executing tail command.');
        }
        return 0;
    }

    /**
     * @param $file
     * @return array
     */
    private function getFileInfo($file): array
    {
        $fp     = explode('/', $file);
        $fex    = end($fp);
        $fx     = explode('.', $fex);
        $fn     = $fx[0];
        $ex     = end($fx);

        array_pop($fp);
        $path = implode('/', $fp);

        return array(
            'file' => $file,
            'out' => $path .'/split/'. $fn,
            'split_path' => $path .'/split/',
            'ext' => $ex,
            'name' => $fn,
            'path' => $path,
        );
    }
}
