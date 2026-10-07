<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Seeder;

class StockMovementSeeder extends Seeder
{
    /**
     * Seed demo products with a coherent movement history.
     * Intended for a fresh database (php artisan migrate:fresh --seed).
     */
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create();

        $products = [
            ['name' => 'Wireless mouse', 'sku' => 'DEMO-001'],
            ['name' => 'Mechanical keyboard', 'sku' => 'DEMO-002'],
            ['name' => 'USB-C cable', 'sku' => 'DEMO-003'],
            ['name' => 'Laptop stand', 'sku' => 'DEMO-004'],
        ];

        $inComments = ['Supplier delivery', 'Restock', 'Customer return'];
        $outComments = ['Customer order', 'Damaged item', 'Internal use'];

        foreach ($products as $data) {
            $product = Product::create($data + ['quantity' => 0, 'alert_threshold' => 5]);

            $stock = 0;

            // 30 days of history, oldest first, so stock_after stays consistent
            for ($day = 30; $day >= 1; $day--) {
                // Only allow outgoing movements when there is stock to remove
                $type = ($stock > 0 && fake()->boolean(50)) ? 'out' : 'in';

                $quantity = $type === 'out'
                    ? fake()->numberBetween(1, min($stock, 15))
                    : fake()->numberBetween(5, 25);

                $stock += $type === 'in' ? $quantity : -$quantity;

                (new StockMovement())->forceFill([
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'type' => $type,
                    'quantity' => $quantity,
                    'stock_after' => $stock,
                    'comment' => fake()->randomElement($type === 'in' ? $inComments : $outComments),
                    'created_at' => now()->subDays($day),
                ])->save();
            }

            $product->update(['quantity' => $stock]);
        }
    }
}
