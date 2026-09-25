<?php

namespace Database\Factories;

use App\Enums\SampleType;
use App\Enums\SiteCode;
use App\Models\Sample;
use Database\Factories\Concerns\AssignsSite;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends Factory<Sample>
 */
class SampleFactory extends Factory
{
    use AssignsSite;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'site_id' => $this->siteId(SiteCode::Research),
            'type' => fake()->randomElement(SampleType::cases()),
            'title' => $title,
            'slug' => Str::slug($title),
            'short_description' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'file_path' => null,
            'is_published' => true,
            'sort_order' => fake()->numberBetween(0, 20),
        ];
    }

    public function ofType(SampleType $type): static
    {
        return $this->state(fn (array $attributes) => ['type' => $type]);
    }

    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) => ['is_published' => false]);
    }

    /**
     * A sample with a stored PDF on the private documents disk.
     */
    public function withDocument(string $contents = "%PDF-1.4\n%sample\n"): static
    {
        return $this->state(function (array $attributes) use ($contents) {
            $path = 'samples/documents/'.Str::random(20).'.pdf';
            Storage::disk(Sample::DOCUMENT_DISK)->put($path, $contents);

            return ['file_path' => $path, 'file_name' => 'original name.pdf'];
        });
    }
}
