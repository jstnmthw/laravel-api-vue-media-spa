<?php

namespace Tests\Unit;

use App\Media;
use Database\Seeders\MediaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
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

    public function setUp() : void
    {
        parent::setUp();
        $this->seed(MediaSeeder::class);
    }

    /**
     * Test MediaController's default api listing (index).
     * @return void
     */
    public function test_api_get_default_listing() : void
    {
        $response = $this->get('/api/media');
        $response->assertStatus(200);
        $response->assertJsonStructure($this->paginateJsonStructure);
    }

    /**
     * Test MediaController's by title.
     * @return void
     */
    public function test_api_get_model_by_title() : void
    {
        sleep(1);
        $title = Str::slug(Media::query()->first()->title);
        $response = $this->get('/api/media/'.$title);
        $response->assertStatus(200);
        $response->assertJsonStructure($this->esJsonStructure);
    }

    /**
     * Test MediaController's api best documents.
     */
    public function test_api_get_model_best() : void
    {
        sleep(1);
        $response = $this->get('/api/media/best');
        $response->assertStatus(200);
        $response->assertJsonStructure($this->paginateJsonStructure);
    }

    /**
     * Test MediaController's api increment dislikes.
     * @return void
     */
    public function test_api_increment_dislikes_column() : void
    {
        $response = $this->post('/api/media/1/dislike');
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response2 = $this->post('/api/media/-1/dislike');
        $response2->assertStatus(404);
    }

    /**
     * Test MediaController's api increment likes.
     * @return void
     */
    public function test_api_increment_likes_column() : void
    {
        $response = $this->post('/api/media/1/like');
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response2 = $this->post('/api/media/-1/like');
        $response2->assertStatus(404);
    }

    /**
     * Test MediaController's api related documents.
     */
    public function test_api_get_related_models() : void
    {
        $response = $this->post('/api/media/related', ['id' => 1]);
        $response->assertStatus(200);
        $response->assertJsonStructure($this->paginateJsonStructure);
    }
}
