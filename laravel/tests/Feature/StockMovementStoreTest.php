<?php

use App\Models\Product;
use App\Models\User;

it('increases stock and records a movement on an incoming entry', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $product = Product::factory()->create(['quantity' => 10]);

    $this->actingAs($user)
        ->post(route('movements.store', $product), [
            'type' => 'in',
            'quantity' => 5,
            'comment' => 'Delivery',
        ])
        ->assertRedirect(route('products.index'));

    expect($product->fresh()->quantity)->toBe(15);

    $this->assertDatabaseHas('stock_movements', [
        'product_id' => $product->id,
        'user_id' => $user->id,
        'type' => 'in',
        'quantity' => 5,
        'stock_after' => 15,
    ]);
});

it('decreases stock and records a movement on an outgoing entry', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $product = Product::factory()->create(['quantity' => 10]);

    $this->actingAs($user)
        ->post(route('movements.store', $product), [
            'type' => 'out',
            'quantity' => 4,
            'comment' => 'Sale',
        ])
        ->assertRedirect(route('products.index'));

    expect($product->fresh()->quantity)->toBe(6);

    $this->assertDatabaseHas('stock_movements', [
        'product_id' => $product->id,
        'user_id' => $user->id,
        'type' => 'out',
        'quantity' => 4,
        'stock_after' => 6,
    ]);
});

it('rejects an outgoing entry larger than the current stock', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $product = Product::factory()->create(['quantity' => 3]);

    $this->actingAs($user)
        ->post(route('movements.store', $product), [
            'type' => 'out',
            'quantity' => 5,
        ])
        ->assertSessionHasErrors('quantity');

    expect($product->fresh()->quantity)->toBe(3);

    $this->assertDatabaseCount('stock_movements', 0);
});


it('rejects invalid movement data', function (array $payload) {
    $user = User::factory()->create(['is_admin' => false]);
    $product = Product::factory()->create(['quantity' => 10]);

    $this->actingAs($user)
        ->post(route('movements.store', $product), $payload)
        ->assertSessionHasErrors();

    expect($product->fresh()->quantity)->toBe(10);

    $this->assertDatabaseCount('stock_movements', 0);
})->with([
    'quantity is zero' => [['type' => 'in', 'quantity' => 0]],
    'quantity is not a number' => [['type' => 'in', 'quantity' => 'abc']],
    'unknown type' => [['type' => 'sideways', 'quantity' => 5]],
]);

it('redirects guests to login and records nothing', function () {
    $product = Product::factory()->create(['quantity' => 10]);

    $this->post(route('movements.store', $product), [
        'type' => 'in',
        'quantity' => 5,
    ])->assertRedirect(route('login'));

    expect($product->fresh()->quantity)->toBe(10);

    $this->assertDatabaseCount('stock_movements', 0);
});
