<?php

namespace Database\Factories;

use App\Enums\SiteCode;
use App\Models\Faq;
use Database\Factories\Concerns\AssignsSite;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Faq>
 */
class FaqFactory extends Factory
{
    use AssignsSite;

    public function definition(): array
    {
        return [
            'site_id' => $this->siteId(SiteCode::Publications),
            'question' => fake()->unique()->sentence().'?',
            'answer' => fake()->paragraph(),
            'sort_order' => fake()->numberBetween(0, 20),
        ];
    }
}
