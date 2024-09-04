<?php

use Tests\TestCase;
use App\Models\Like;
use App\Models\Post;

use App\Models\User;
use App\Models\Comment;
use function Pest\Laravel\post;
use function Pest\Laravel\actingAs;
use Illuminate\Database\Eloquent\Model;

it('requires authentication', function () {
    post(route('likes.store', ['post', 1]))->assertRedirect(route('login'));
});


it('allows liking a likable', function(Model $likeable) {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user)
        ->from('dashboard')
        ->post(route('likes.store', [ $likeable->getMorphClass(), $likeable->id ]))
        ->assertRedirect(route('dashboard'));

    $this->assertDatabaseHas(Like::class, [
        'user_id' => $user->id,
        'likeable_id' => $likeable->id,
        'likeable_type' => $likeable->getMorphClass(),
    ]);

    expect($likeable->refresh()->likes_count)->toBe(1);

})->with([
    fn() => Post::factory()->create(),
    fn() => Comment::factory()->create(),
]);

it('prevents liking something you already liked', function() {
    $like = Like::factory()->create();
    /** @var Model $likeable */
    $likeable = $like->likeable;

    actingAs($like->user)
        ->post(route('likes.store', [ $likeable->getMorphClass(), $likeable->id ]))
        ->assertForbidden();
});

it('only allows liking supported models', function() {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('likes.store', [ $user->getMorphClass(), $user->id ]))
        ->assertForbidden();
});

it('throws 404 if the type is not supported', function() {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('likes.store', [ 'foo', 1 ]))
        ->assertNotFound();
});
