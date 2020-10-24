<?php

use Illuminate\Database\Seeder;
use Database\Seeders\MediaSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RootSeeder::class);
        $this->call(MediaSeeder::class);
    }
}
