<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

// uses(RefreshDatabase::class);

test('guests are redirected to the login page', function (): void {
    $response = $this->get('/');
    $response->assertRedirect('/login');
});

test('authenticated users can visit the dashboard', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/');
    $response->assertStatus(200);
});
