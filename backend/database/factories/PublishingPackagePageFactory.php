<?php

namespace Database\Factories;

use App\Models\PublishingPackagePage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<PublishingPackagePage>
 */
class PublishingPackagePageFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->words(2, true);

        return [
            'slug' => Str::slug($title),
            'eyebrow' => 'RTS PACKAGE DETAIL',
            'title' => ucfirst($title),
            'heading' => fake()->sentence(),
            'copy' => fake()->paragraph(),
            'benefits' => fake()->sentences(3),
        ];
    }
}
