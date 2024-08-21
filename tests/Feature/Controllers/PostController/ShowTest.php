<?php
use App\Http\Resources\CommentResource;
use App\Models\Post;
use App\Models\Comment;

use function Pest\Laravel\get;
use App\Http\Resources\PostResource;

it('can show a post', function () {
    $post = Post::factory()->create();

    get($post->showRoute())
        ->assertComponent('Posts/Show');
});

it('passed post to the view', function () {
    $post = Post::factory()->create();
    $post->load('user');

    get($post->showRoute())
        ->assertHasResource('post', PostResource::make($post));
});

it('passed comments to the view', function () {
    $post = Post::factory()->create();
    $comments = Comment::factory(3)->for($post)->create();
    $comments->load('user');

    get($post->showRoute())
        ->assertHasPaginatedResource('comments', CommentResource::collection($comments->reverse()));
});

it('will redirect if the slug is incorrect', function() {
    $post = Post::factory()->create();

    get(route('posts.show', [$post, 'foo-bar']))
        ->assertRedirect($post->showRoute());
});
