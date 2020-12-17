<?php

namespace Database\Seeders;

use App\Media;
use Illuminate\Database\Seeder;

class MediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Media::factory()
            ->times(config('const.media_seed'))
            ->create();
    }
}
