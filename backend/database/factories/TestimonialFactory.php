<?php

namespace Database\Factories;

use App\Enums\SiteCode;
use App\Models\Testimonial;
use Database\Factories\Concerns\AssignsSite;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Testimonial>
 */
class TestimonialFactory extends Factory
{
    use AssignsSite;

    public function definition(): array
    {
        return [
            'site_id' => $this->siteId(SiteCode::Research),
            'name' => fake()->name(),
            'role' => fake()->jobTitle(),
            'quote' => fake()->sentence(12),
            'rating' => fake()->numberBetween(4, 5),
            'sort_order' => fake()->numberBetween(0, 20),
            'is_published' => true,
        ];
    }
}
