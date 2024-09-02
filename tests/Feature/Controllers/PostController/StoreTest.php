<?php
use Tests\TestCase;
use App\Models\Post;
use App\Models\Topic;
use App\Models\User;

use Illuminate\Support\Str;
use function Pest\Laravel\post;
use function Pest\Laravel\actingAs;

beforeEach(function() {
    $this->data['valid'] = fn() => [
        'title' => str_repeat('a', 10),
        'topic_id' => Topic::factory()->create()->getKey(),
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
    $data = value($this->data['valid']);

    actingAs($user)->post(route('posts.store'), $data);

    $this->assertDatabaseHas(Post::class, [
        ...$data,
        'title' => Str::title($data['title']),
        'user_id' => $user->id
    ]);
});

it('redirects to the post show page', function() {
    /** @var TestCase $this */
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('posts.store'), value($this->data['valid']))
        ->assertRedirect(Post::latest('id')->first()->showRoute());
});

it('requires a valid data', function(array $invalidData, array | string $errors) {
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('posts.store', [
            ...value($this->data['valid']),
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
    [['topic_id' => null], 'topic_id'],
    [['topic_id' => -1], 'topic_id'],

    [['body' => null,], 'body'],
    [['body' => true,], 'body'],
    [['body' => 1,], 'body'],
    [['body' => 1.5,], 'body'],
    [['body' => str_repeat('a', 10_001),], 'body'],
    [['body' => str_repeat('a', 99),], 'body'],
]);
