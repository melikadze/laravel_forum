<?php

use Tests\TestCase;
use App\Models\User;

use App\Models\Comment;
use function Pest\Laravel\delete;
use function Pest\Laravel\actingAs;

it('requires authentication', function() {

    delete(route('comments.destroy', Comment::factory()->create()))
        ->assertRedirect(route('login'));

});


it('can delete a comment', function() {
    /** @var TestCase $this */

    $comment = Comment::factory()->create();


    actingAs($comment->user)

        ->delete(route('comments.destroy', $comment));

    $this->assertModelMissing($comment);
});


it('redirects to the post show page', function() {
    /** @var TestCase $this */

    $comment = Comment::factory()->create();


    actingAs($comment->user)

        ->delete(route('comments.destroy', $comment))

        ->assertRedirect(route('posts.show', $comment->post_id));
});


it('prevents deleting a comment you did not create', function() {

    /** @var TestCase $this */

    $comment = Comment::factory()->create();

    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user)

        ->delete(route('comments.destroy', $comment))

        ->assertForbidden();

});
