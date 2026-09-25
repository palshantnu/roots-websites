<?php

namespace Database\Factories;

use App\Enums\SiteCode;
use App\Models\Post;
use Database\Factories\Concerns\AssignsSite;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    use AssignsSite;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'site_id' => $this->siteId(SiteCode::Publications),
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
