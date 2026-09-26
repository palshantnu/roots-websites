<?php

namespace Database\Factories;

use App\Enums\SiteCode;
use App\Models\ContactMessage;
use Database\Factories\Concerns\AssignsSite;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactMessage>
 */
class ContactMessageFactory extends Factory
{
    use AssignsSite;

    public function definition(): array
    {
        return [
            'site_id' => $this->siteId(SiteCode::Publications),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'subject' => fake()->sentence(),
            'message' => fake()->paragraph(),
            'read' => false,
        ];
    }
}
