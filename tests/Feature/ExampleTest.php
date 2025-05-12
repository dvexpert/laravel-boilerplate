<?php

use App\Models\User;

use function Pest\Laravel\actingAs;

it('returns a successful response', function (): void {
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user)
        ->get('/')
        ->assertStatus(200);
});
