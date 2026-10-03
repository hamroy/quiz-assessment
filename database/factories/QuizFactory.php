<?php

namespace Database\Factories;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizFactory extends Factory
{
    protected $model = Quiz::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'slug' => fake()->slug(),
            'description' => fake()->optional()->paragraph(),
            'type' => fake()->randomElement(['general', 'stress', 'anxiety', 'mbti', 'disc', 'big_five']),
            'status' => fake()->randomElement(['draft', 'published', 'archived']),
            'published_at' => fake()->optional()->dateTimeThisYear(),
        ];
    }
}
