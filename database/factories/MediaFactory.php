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
        return [
            'embed' => $this->faker->youtubeShortUri(),
            'thumbnail' => 'https://picsum.photos/205/155/?image='.$this->faker->randomDigit,
            'album' => 'https://picsum.photos/205/155/?image='.$this->faker->randomDigit.';'.'https://picsum.photos/205/155/?image='.$this->faker->randomDigit.';'.'https://picsum.photos/205/155/?image='.$this->faker->randomDigit,
            'title' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'categories' => $this->faker->word .";". $this->faker->word .";". $this->faker->word,
            'author' => $this->faker->name,
            'duration' => $this->faker->numberBetween($min = 30, $max = 1000),
            'views' => $this->faker->numberBetween($min = 500, $max = 1000000),
            'likes' => $this->faker->numberBetween($min = 500, $max = 1000000),
            'dislikes' => $this->faker->numberBetween($min = 500, $max = 1000000),
        ];
    }
}
