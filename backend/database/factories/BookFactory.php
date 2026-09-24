<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'author_id' => Author::factory(),
            'category_id' => Category::factory(),
            'price' => fake()->numberBetween(199, 999),
            'original_price' => fake()->numberBetween(1000, 1500),
            'color' => fake()->hexColor(),
            'cover_image' => null,
            'isbn' => fake()->isbn13(),
            'pages' => fake()->numberBetween(100, 500),
            'language' => 'English',
            'format' => 'Paperback',
            'rating' => 4.5,
            'review_count' => fake()->numberBetween(0, 100),
            'featured' => false,
            'bestseller' => false,
            'new_release' => false,
            'description' => fake()->paragraph(),
        ];
    }
}
