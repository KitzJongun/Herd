<?php

namespace Database\Factories;

use App\Enums\PostStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->sentence(rand(5, 10));

        return [
            'user_id'      => User::factory(),
            'title'        => $title,
            'slug'         => Str::slug($title),
            'body'         => fake()->paragraphs(rand(5, 10), asText: true),
            'excerpt'      => fake()->paragraph(2),
            'status'       => PostStatus::Draft,
            'published_at' => null,
            'metadata'     => [
                'seo_title'       => $title,
                'seo_description' => fake()->sentence(15),
                'og_image'        => null,
            ],
            'view_count'   => fake()->numberBetween(0, 5000),
        ];
    }

    /** State: post berstatus Published dengan tanggal publish. */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'       => PostStatus::Published,
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ]);
    }

    /** State: post berstatus Archived. */
    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'       => PostStatus::Archived,
            'published_at' => fake()->dateTimeBetween('-2 years', '-6 months'),
        ]);
    }
}
