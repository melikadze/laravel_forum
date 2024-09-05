<?php

use Tests\TestCase;
use App\Models\Like;
use App\Models\Post;

use App\Models\User;
use App\Models\Comment;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;

use Illuminate\Database\Eloquent\Model;

it('requires authentication', function () {
    delete(route('likes.destroy', ['post', 1]))->assertRedirect(route('login'));
});


it('allows unliking a likable', function(Model $likeable) {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    $like = Like::factory()->for($user)->for($likeable, 'likeable')->create();

    actingAs($user)
        ->from('dashboard')
        ->delete(route('likes.store', [ $likeable->getMorphClass(), $likeable->id ]))
        ->assertRedirect(route('dashboard'));

    $this->assertDatabaseEmpty(Like::class);

    expect($likeable->refresh()->likes_count)->toBe(0);

})->with([
    fn() => Post::factory()->create(['likes_count' => 1]),
    fn() => Comment::factory()->create(['likes_count' => 1]),
]);

it('prevents unliking something you havent liked', function() {
    /** @var Model $likeable */
    $likeable = Post::factory()->create();
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user)
        ->delete(route('likes.destroy', [ $likeable->getMorphClass(), $likeable->id ]))
        ->assertForbidden();
});

it('only allows unliking supported models', function() {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user)
        ->delete(route('likes.destroy', [ $user->getMorphClass(), $user->id ]))
        ->assertForbidden();
});

it('throws 404 if the type is not supported', function() {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user)
        ->delete(route('likes.destroy', [ 'foo', 1 ]))
        ->assertNotFound();
});
