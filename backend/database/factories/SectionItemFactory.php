<?php

namespace Database\Factories;

use App\Enums\SiteCode;
use App\Models\SectionItem;
use Database\Factories\Concerns\AssignsSite;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SectionItem>
 */
class SectionItemFactory extends Factory
{
    use AssignsSite;

    public function definition(): array
    {
        return [
            'site_id' => $this->siteId(SiteCode::Research),
            'section' => 'process_steps',
            'title' => fake()->sentence(3),
            'description' => fake()->sentence(),
            'sort_order' => fake()->numberBetween(0, 20),
            'is_published' => true,
        ];
    }
}
