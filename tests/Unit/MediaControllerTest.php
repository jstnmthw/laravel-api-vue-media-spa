<?php

namespace Tests\Unit;

use App\Media;
use Database\Seeders\MediaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MediaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_get_default_listing()
    {
        $this->seed(MediaSeeder::class);
        $response = $this->get('/api/media');
        $response->assertStatus(200);
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
        ->assertJson([
            "id" => true,
            "embed" => true,
            "thumbnail" => true,
            "album" => true,
            "title" => true,
            "categories" => true,
            "author" => true,
            "duration" => true,
            "views" => true,
            "likes" => true,
            "dislikes" => true,
            "created_at" => true,
            "updated_at" => true,
        ]);
    }
}
