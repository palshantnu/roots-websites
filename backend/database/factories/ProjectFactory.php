<?php

namespace Database\Factories;

use App\Enums\SiteCode;
use App\Models\Project;
use Database\Factories\Concerns\AssignsSite;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    use AssignsSite;

    public function definition(): array
    {
        $name = fake()->unique()->company();

        return [
            'site_id' => $this->siteId(SiteCode::It),
            'name' => $name,
            'slug' => Str::slug($name),
            'category' => fake()->randomElement(['Website', 'Mobile Apps', 'Software']),
            'industry' => fake()->word(),
            'technologies' => ['React', 'Node.js'],
            'description' => fake()->sentence(),
            'year' => 2025,
            'is_featured' => false,
            'sort_order' => fake()->numberBetween(0, 20),
            'is_published' => true,
        ];
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => ['is_featured' => true]);
    }
}
