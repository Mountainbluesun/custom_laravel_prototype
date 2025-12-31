<?php

use App\Models\User;
use App\Models\Product;
use App\Models\StockMovement;

it('denies non-admin access to global movements page', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->get('/movements')
        ->assertForbidden(); // 403
});

it('allows admin access to global movements page', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->get('/movements')
        ->assertOk(); // 200
});

it('denies non-admin export', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->get('/movements/export')
        ->assertForbidden(); // 403
});

it('allows admin export', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    expect($admin->is_admin)->toBeTrue();
    expect($admin->fresh()->is_admin)->toBeTrue();

    $this->actingAs($admin)

        ->get('/movements/export')
        ->assertOk(); // 200
});

it('allows any authenticated user to access products index', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->get('/products')
        ->assertOk();
});
