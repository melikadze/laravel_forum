<?php

namespace Database\Seeders;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Database\Seeder;
use function Laravel\Prompts\progress;

use Illuminate\Support\LazyCollection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LikeLoadTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $post = Post::find(1);

        $progress = progress('Creating likes', 500_000);

        $progress->start();
        LazyCollection::times(5000)->each( function() use($post, $progress) {
            Like::factory(100)->for($post, 'likeable')->create();
            $progress->advance();
        });
        $progress->finish();

    }
}
