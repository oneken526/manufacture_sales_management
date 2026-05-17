<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code'        => strtoupper($this->faker->unique()->bothify('P-####')),
            'name'        => $this->faker->word() . '商品',
            'category_id' => ProductCategory::factory(),
            'unit_price'  => $this->faker->numberBetween(100, 100000),
            'unit_name'   => '個',
            'notes'       => null,
        ];
    }
}
