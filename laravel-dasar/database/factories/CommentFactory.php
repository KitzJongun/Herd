<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'post_id'     => Post::factory(),
            'user_id'     => User::factory(),
            'parent_id'   => null,
            'body'        => fake()->paragraph(rand(1, 3)),
            'is_approved' => fake()->boolean(75),
        ];
    }

    /** State: komentar yang sudah disetujui. */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => true,
        ]);
    }

    /** State: komentar sebagai reply dari komentar lain. */
    public function asReply(int $parentId): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => $parentId,
        ]);
    }
}
