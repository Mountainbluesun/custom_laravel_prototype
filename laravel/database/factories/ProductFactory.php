<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'sku' => fake()->unique()->bothify('SKU-####-??'),
            'quantity' => 10,
            'alert_threshold' => 0,
        ];
    }
}
