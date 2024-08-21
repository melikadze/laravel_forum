<?php
use Tests\TestCase;
use App\Models\User;

use App\Models\Comment;
use function Pest\Laravel\delete;
use function Pest\Laravel\actingAs;

it('requires authentication', function () {

    delete(route('comments.destroy', Comment::factory()->create()))
        ->assertRedirect(route('login'));

});

it('can delete a comment', function () {
    /** @var TestCase $this */

    $comment = Comment::factory()->create();


    actingAs($comment->user)

        ->delete(route('comments.destroy', $comment));

    $this->assertModelMissing($comment);
});

it('redirects to the post show page with the page query parameter', function () {
    /** @var TestCase $this */

    $comment = Comment::factory()->create();


    actingAs($comment->user)

        ->delete(route('comments.destroy', [ 'comment' => $comment, 'page' => 2 ]))

        ->assertRedirect($comment->post->showRoute(['page' => 2]));
});

it('redirects to the post show page', function () {
    /** @var TestCase $this */

    $comment = Comment::factory()->create();


    actingAs($comment->user)

        ->delete(route('comments.destroy', $comment))

        ->assertRedirect($comment->post->showRoute());
});

it('prevents deleting a comment you did not create', function () {

    /** @var TestCase $this */

    $comment = Comment::factory()->create();

    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user)

        ->delete(route('comments.destroy', $comment))

        ->assertForbidden();
});

it('prevents deleting a comment posted over an hour ago', function () {

    /** @var TestCase $this */

    $this->freezeTime();

    $comment = Comment::factory()->create();


    $this->travel(1)->hour();


    actingAs($comment->user)

        ->delete(route('comments.destroy', $comment))

        ->assertForbidden();
});
