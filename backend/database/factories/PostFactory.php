<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'category' => fake()->randomElement(['Writing', 'Design', 'Publishing']),
            'color' => fake()->hexColor(),
            'read_time' => fake()->numberBetween(3, 8).' min read',
            'excerpt' => fake()->sentence(),
            'body' => fake()->paragraphs(3, true),
            'published_at' => fake()->date(),
        ];
    }
}
