<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'      => User::factory(),
            'avatar'       => null,
            'bio'          => fake()->paragraph(2),
            'website'      => fake()->optional(0.6)->url(),
            'social_links' => [
                'twitter'   => '@' . fake()->userName(),
                'instagram' => '@' . fake()->userName(),
                'linkedin'  => 'https://linkedin.com/in/' . fake()->slug(),
            ],
        ];
    }

    /** State: profile tanpa social links. */
    public function withoutSocials(): static
    {
        return $this->state(fn (array $attributes) => [
            'social_links' => null,
        ]);
    }
}
