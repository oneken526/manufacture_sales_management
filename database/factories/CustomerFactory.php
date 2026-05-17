<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    public function definition(): array
    {
        static $seq = 1;

        return [
            'code'         => sprintf('C%04d', $seq++),
            'name'         => $this->faker->company(),
            'name_kana'    => null,
            'postal_code'  => null,
            'address'      => null,
            'phone'        => null,
            'email'        => $this->faker->optional()->companyEmail(),
            'closing_day'  => 99,
            'credit_limit' => 0,
            'notes'        => null,
        ];
    }
}
