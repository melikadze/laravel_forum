<?php

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

use function Pest\Laravel\get;
use Inertia\Testing\AssertableInertia;

it('should return the correct component', function () {
    Post::factory()->create();
    get(route('posts.index'))
        ->assertInertia( fn(AssertableInertia $inertia) => $inertia
        ->component('Posts/Index', true)
    );
});

it('passes posts to the view', function() {

    $posts = Post::factory(3)->create();

    get(route('posts.index'))

        ->assertHasPaginatedResource('posts', PostResource::collection($posts->reverse()));
});
