<?php

namespace Database\Factories;

use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Package>
 */
class PackageFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'price' => '₹'.fake()->numberBetween(10, 90).',999',
            'tone' => fake()->randomElement(['sage', 'coral', 'ink']),
            'popular' => false,
            'features' => fake()->sentences(3),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
