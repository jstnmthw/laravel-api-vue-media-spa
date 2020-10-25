<?php

namespace Tests\Unit;

use App\Media;
use Database\Seeders\MediaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MediaControllerTest extends TestCase
{
    use RefreshDatabase;

    private $esJsonStructure = [
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
    ];
    private $paginateJsonStructure = [
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
    ];

    /**
     * Test MediaController's default api listing (index).
     * @return void
     */
    public function test_api_get_default_listing() : void
    {
        $this->seed(MediaSeeder::class);
        $response = $this->get('/api/media');
        $response->assertStatus(200);
        $response->assertJsonStructure($this->paginateJsonStructure);
    }

    /**
     * Test MediaController's get by id for return json or 404 return.
     * @return void
     */
    public function test_api_get_model_by_id() : void
    {
        $response = $this->get('/api/media/1');
        $response->assertStatus(404);
        $this->seed(MediaSeeder::class);
        $response = $this->get('/api/media/1');
        $response->assertStatus(200);
        $response->assertJsonStructure($this->esJsonStructure);
    }

    /**
     * Test MediaController's `like` (increment column) function.
     * @return void
     */
    public function test_api_increment_likes_column() : void
    {
        $this->seed(MediaSeeder::class);
        $model = Media::query()->select('likes')->first();
        $response = $this->post('/api/media/1/like');
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response2 = $this->post('/api/media/-1/like');
        $response2->assertStatus(404);
    }
}
