<?php

namespace Database\Seeders;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Topic;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(TopicSeeder::class);

        $topics = Topic::all();

        $users = User::factory(10)->create();

        $posts = Post::factory(50)
            ->withFixture()
            ->has(Comment::factory(20)->recycle($users))
            ->recycle([$users, $topics])->create();



        $chad = User::factory()
        ->has(Post::factory(1)->recycle($topics)->withFixture())
        ->has(Comment::factory(10)->recycle($posts))
        ->has(Like::factory()->forEachSequence(
            ...$posts->random(1)->map( fn(Post $post) => [ 'likeable_id' => $post ]),
        ))
        ->create([
            'name' => 'Melikadze',
            'email' => 'melikadze@live.com',
        ]);
    }
}
