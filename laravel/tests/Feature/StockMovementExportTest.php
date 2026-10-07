<?php

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;

it('includes the stock after each movement in the CSV export', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $product = Product::factory()->create(['name' => 'Test widget']);

    StockMovement::create([
        'product_id' => $product->id,
        'user_id' => $admin->id,
        'type' => 'in',
        'quantity' => 5,
        'stock_after' => 42,
        'comment' => 'Delivery',
    ]);

    $response = $this->actingAs($admin)->get('/movements/export');

    $response->assertOk();

    $csv = $response->streamedContent();

    expect($csv)->toContain('stock_after');
    expect($csv)->toContain('Test widget');
    expect($csv)->toContain(',42,');
});
