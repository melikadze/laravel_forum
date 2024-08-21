<?php
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;

use Illuminate\Support\Str;
use function Pest\Laravel\post;
use function Pest\Laravel\actingAs;

beforeEach(function() {
    $this->data['valid'] = [
        'title' => str_repeat('a', 10),
        'body' => str_repeat('a', 100)
    ];
});

it('requires authentication', function() {
    post(route('posts.store'))->assertRedirect(route('login'));
});

it('stores a post', function() {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user)->post(route('posts.store'), $this->data['valid']);

    $this->assertDatabaseHas(Post::class, [
        ...$this->data['valid'],
        'title' => Str::title($this->data['valid']['title']),
        'user_id' => $user->id
    ]);
});

it('redirects to the post show page', function() {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('posts.store'), $this->data['valid'])
        ->assertRedirect(Post::latest('id')->first()->showRoute());
});

it('requires a valid data', function(array $invalidData, array | string $errors) {
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('posts.store', [
            ...$this->data['valid'],
            ...$invalidData
        ]))
        ->assertInvalid($errors);
})->with([
    [['title' => null], 'title'],
    [['title' => true], 'title'],
    [['title' => 1], 'title'],
    [['title' => 1.5], 'title'],
    [['title' => str_repeat('a', 121)], 'title'],
    [['title' => str_repeat('a', 9)], 'title'],

    [['body' => null,], 'body'],
    [['body' => true,], 'body'],
    [['body' => 1,], 'body'],
    [['body' => 1.5,], 'body'],
    [['body' => str_repeat('a', 10_001),], 'body'],
    [['body' => str_repeat('a', 99),], 'body'],
]);
