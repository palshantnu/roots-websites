<?php

namespace Database\Factories;

use App\Models\PublishEnquiry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PublishEnquiry>
 */
class PublishEnquiryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'full_name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'location' => fake()->city(),
            'book_title' => fake()->sentence(3),
            'genre' => fake()->word(),
            'language' => 'English',
            'word_count' => (string) fake()->numberBetween(5000, 80000),
            'formats' => ['Paperback', 'E-book'],
            'manuscript_path' => null,
            'status' => 'new',
        ];
    }
}
