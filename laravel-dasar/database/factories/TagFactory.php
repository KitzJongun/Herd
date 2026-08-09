<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    private array $tagNames = [
        'Laravel', 'PHP', 'Vue.js', 'React', 'JavaScript',
        'TypeScript', 'MySQL', 'PostgreSQL', 'Docker', 'REST API',
        'GraphQL', 'Testing', 'CI/CD', 'Linux', 'Git',
        'Tailwind CSS', 'Node.js', 'Python', 'DevOps', 'Security',
    ];

    public function definition(): array
    {
        $name = fake()->unique()->randomElement($this->tagNames);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
