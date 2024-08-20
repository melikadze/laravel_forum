<?php

use App\Models\User;
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
