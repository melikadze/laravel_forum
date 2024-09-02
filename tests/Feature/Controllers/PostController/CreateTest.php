<?php

use App\Http\Resources\TopicResource;
use App\Models\User;
use App\Models\Topic;
use function Pest\Laravel\get;
use function Pest\Laravel\actingAs;

it('requires authentication', function() {
    get(route('posts.create'))->assertRedirect(route('login'));
});

it('returns the correct component', function() {
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('posts.create'))
        ->assertComponent('Posts/Create');
});

it('passes topics to the view', function() {
    /** @var User $user */
    $user = User::factory()->create();
    $topics = Topic::factory(2)->create();

    actingAs($user)
        ->get(route('posts.create'))
        ->assertHasResource('topics', TopicResource::make($topics));
});
