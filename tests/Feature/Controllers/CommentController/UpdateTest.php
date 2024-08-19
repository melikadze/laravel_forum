<?php

use Tests\TestCase;
use App\Models\User;

use App\Models\Comment;
use function Pest\Laravel\delete;
use function Pest\Laravel\actingAs;


it('require authentication', function() {

    delete(route('comments.update', Comment::factory()->create()))
        ->assertRedirect(route('login'));

});


it('can update a comment', function() {
    /** @var TestCase $this */

    $comment = Comment::factory()->create(['body' => 'This is the old body']);

    $newBody = 'This is new body';


    actingAs($comment->user)

        ->put(route('comments.update', $comment), [ 'body' => $newBody ]);


    $this->assertDatabaseHas(Comment::class, [

        'id' => $comment->id,

        'body' => $newBody,

    ]);


});


it('redirects to the post show page', function() {
    /** @var TestCase $this */

    $comment = Comment::factory()->create();

    actingAs($comment->user)

        ->put(route('comments.update', $comment), [ 'body' => 'This is new body' ])

        ->assertRedirect(route('posts.show', $comment->post));


});


it('redirects to the correct page of comments', function() {
    /** @var TestCase $this */

    $comment = Comment::factory()->create();

    actingAs($comment->user)

        ->put(route('comments.update', [ 'comment' => $comment, 'page' => 2 ]), [ 'body' => 'This is new body'])

        ->assertRedirect(route('posts.show', [ 'post' => $comment->post, 'page' => 2 ]));

});


it('can not update a comment from another user', function() {
    /** @var TestCase $this */

    $comment = Comment::factory()->create();

    /** @var User $user */
    $user = User::factory()->create();


    actingAs($user)

        ->put(route('comments.update', $comment), [ 'body' => 'This is new body'])

        ->assertForbidden();
});


it('require a valid body', function($value) {
    /** @var TestCase $this */

    $comment = Comment::factory()->create();


    actingAs($comment->user)

        ->put(route('comments.update', $comment), [ 'body' => $value])

        ->assertInvalid('body');


})->with([

    null,

    true,

    1,

    1.5,

    str_repeat('a', 2501)

]);
