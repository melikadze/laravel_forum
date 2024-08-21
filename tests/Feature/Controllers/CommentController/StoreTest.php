<?php
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Tests\TestCase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;


it('requires authentication', function() {

    post(route('posts.comments.store', Post::factory()->create()))
        ->assertRedirect(route('login'));

});

it('can store a comment', function() {
    /** @var TestCase $this */

    $user = User::factory()->create();

    $post = Post::factory()->create();

    /** @var User $user */
    actingAs($user)->post(route('posts.comments.store', $post), [

        'body' => 'This is a comment'

    ]);



    $this->assertDatabaseHas(Comment::class, [

        'post_id' => $post->id,

        'user_id' => $user->id,

        'body' => 'This is a comment'

    ]);

});

it('redirects to the post show page', function() {
    /** @var TestCase $this */

    $user = User::factory()->create();

    $post = Post::factory()->create();

    /** @var User $user */
    actingAs($user)->post(route('posts.comments.store', $post), [

        'body' => 'This is a comment'

    ])
        ->assertRedirect($post->showRoute());


});

it('requires a valid body', function($value) {
    /** @var TestCase $this */

    $user = User::factory()->create();

    $post = Post::factory()->create();

    /** @var User $user */
    actingAs($user)->post(route('posts.comments.store', $post), [

        'body' => $value

    ])
        ->assertInvalid('body');


})->with([
    null,

    1,

    1.5,

    true,

    str_repeat('a', 2501)

]);
