<?php

namespace Database\Factories;

use App\Enums\SiteCode;
use App\Models\SitePage;
use Database\Factories\Concerns\AssignsSite;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<SitePage>
 */
class SitePageFactory extends Factory
{
    use AssignsSite;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'site_id' => $this->siteId(SiteCode::Research),
            'slug' => Str::slug($name),
            'name' => ucfirst($name),
            'eyebrow' => fake()->words(2, true),
            'title' => fake()->sentence(5),
            'description' => fake()->sentence(),
            'meta_title' => fake()->sentence(4),
            'meta_description' => fake()->sentence(),
            'is_published' => true,
        ];
    }
}
