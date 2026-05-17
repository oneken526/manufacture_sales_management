<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductCategory>
 */
class ProductCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'       => $this->faker->word() . 'カテゴリ',
            'parent_id'  => null,
            'sort_order' => $this->faker->numberBetween(0, 100),
        ];
    }
}
