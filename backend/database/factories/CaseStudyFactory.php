<?php

namespace Database\Factories;

use App\Enums\SiteCode;
use App\Models\CaseStudy;
use Database\Factories\Concerns\AssignsSite;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<CaseStudy>
 */
class CaseStudyFactory extends Factory
{
    use AssignsSite;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(5);

        return [
            'site_id' => $this->siteId(SiteCode::It),
            'title' => $title,
            'slug' => Str::slug($title),
            'client' => fake()->company(),
            'industry' => fake()->word(),
            'challenge' => fake()->sentence(),
            'solution' => fake()->sentence(),
            'technologies' => ['React'],
            'results' => [['value' => '40%', 'label' => 'Faster delivery']],
            'sort_order' => fake()->numberBetween(0, 20),
            'is_published' => true,
        ];
    }
}
