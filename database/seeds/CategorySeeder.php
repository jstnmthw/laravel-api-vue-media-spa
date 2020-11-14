<?php

namespace Database\Seeders;

use App\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::query()->insert([
            [
                'name' => 'General',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'News',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Weather',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Politics',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gaming',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gossip',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'How-to',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Events',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vlog',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
