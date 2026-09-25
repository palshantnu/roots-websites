<?php

namespace Database\Factories;

use App\Enums\SiteCode;
use App\Models\Service;
use Database\Factories\Concerns\AssignsSite;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    use AssignsSite;

    public function definition(): array
    {
        $title = fake()->unique()->words(2, true);

        return [
            'site_id' => $this->siteId(SiteCode::Publications),
            'title' => ucfirst($title),
            'slug' => Str::slug($title),
            'description' => fake()->sentence(),
            'sort_order' => fake()->numberBetween(0, 20),
        ];
    }
}
