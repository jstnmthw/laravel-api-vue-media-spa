<?php

namespace Database\Factories;

use App\Media;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Provider\Youtube;

class MediaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Media::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $this->faker->addProvider(new Youtube($this->faker));

        $categories = [
            'General',
            'News',
            'Weather',
            'Politics',
            'Gaming',
            'Gossip',
            'How-to',
            'Events',
            'Vlog',
        ];

        return [
            'embed' => $this->faker->youtubeEmbedUri(),
            'thumbnail' => 'https://picsum.photos/205/155/?image='.$this->faker->randomDigit,
            'album' => 'https://picsum.photos/205/155/?image='.$this->faker->randomDigit.';'.'https://picsum.photos/205/155/?image='.$this->faker->randomDigit.';'.'https://picsum.photos/205/155/?image='.$this->faker->randomDigit,
            'title' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'categories' => $categories[$this->faker->numberBetween(0,8)].";".$categories[$this->faker->numberBetween(0,8)].";".$categories[$this->faker->numberBetween(0,8)],
            'author' => $this->faker->name,
            'duration' => $this->faker->numberBetween($min = 30, $max = 1000),
            'views' => $this->faker->numberBetween($min = 500, $max = 1000000),
            'likes' => $this->faker->numberBetween($min = 500, $max = 1000000),
            'dislikes' => $this->faker->numberBetween($min = 500, $max = 1000000),
            'created_at' => $this->faker->dateTimeBetween($startDate = '-2 weeks', $endDate = 'now', $timezone = null),
            'updated_at' => $this->faker->dateTimeBetween($startDate = '-2 weeks', $endDate = 'now', $timezone = null),
        ];
    }
}
