<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Profile;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed database dengan data dummy yang realistis.
     */
    public function run(): void
    {
        // ── 1. Buat 10 Tag ──────────────────────────────────────────────
        $tags = Tag::factory(10)->create();

        // ── 2. Buat Admin User ──────────────────────────────────────────
        $admin = User::factory()->create([
            'name'  => 'Rizki Admin',
            'email' => 'admin@example.com',
        ]);
        Profile::factory()->create(['user_id' => $admin->id]);

        // ── 3. Buat 9 User biasa beserta Profile-nya ────────────────────
        $users = User::factory(9)
            ->has(Profile::factory())
            ->create();

        $allUsers = $users->push($admin);

        // ── 4. Buat Post untuk setiap User ──────────────────────────────
        $allUsers->each(function (User $user) use ($tags) {

            // 3 post Published
            $publishedPosts = Post::factory(3)
                ->published()
                ->for($user, 'author')
                ->create();

            // 1 post Draft
            $draftPost = Post::factory(1)
                ->for($user, 'author')
                ->create();

            // Attach 1–3 tag acak ke setiap post
            $publishedPosts->each(function (Post $post) use ($tags, $user) {
                $post->tags()->attach(
                    $tags->random(rand(1, 3))->pluck('id')
                );

                // Tambah 3–8 komentar di setiap post yang published
                Comment::factory(rand(3, 8))
                    ->approved()
                    ->for($post)
                    ->for($user, 'user')
                    ->create();
            });

            $draftPost->each(function (Post $post) use ($tags) {
                $post->tags()->attach(
                    $tags->random(rand(1, 2))->pluck('id')
                );
            });
        });

        $this->command->info('✅ Database berhasil di-seed!');
        $this->command->table(
            ['Model', 'Jumlah'],
            [
                ['User',    User::count()],
                ['Profile', Profile::count()],
                ['Post',    Post::count()],
                ['Comment', Comment::count()],
                ['Tag',     Tag::count()],
            ]
        );
    }
}
