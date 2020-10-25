<?php

namespace Tests\Unit;

use App\Media;
use Database\Seeders\MediaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class MediaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_get_default_listing()
    {
        $this->seed(MediaSeeder::class);
        $response = $this->get('/api/media');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'current_page',
            'data',
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'links',
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total',
        ]);
    }

    /**
     * Test get model by id API Endpoint for return json data or 404.
     * @return void
     */
    public function test_api_get_model_by_id()
    {
        $response = $this->get('/api/media/1');
        $response->assertStatus(404);

        $this->seed(MediaSeeder::class);

        $response = $this->get('/api/media/1');
        $response
        ->assertStatus(200)
        ->assertJsonStructure([
            "id",
            "embed",
            "thumbnail",
            "album",
            "title",
            "categories",
            "author",
            "duration",
            "views",
            "likes",
            "dislikes",
            "created_at",
            "updated_at",
        ]);
    }
}
