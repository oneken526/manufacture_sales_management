<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quotation>
 */
class QuotationFactory extends Factory
{
    public function definition(): array
    {
        static $seq = 1;

        return [
            'quotation_number' => sprintf('Q%s-%03d', now()->format('Ym'), $seq++),
            'customer_id'      => Customer::factory(),
            'user_id'          => User::factory(),
            'valid_until'      => $this->faker->dateTimeBetween('+1 month', '+3 months')->format('Y-m-d'),
            'status'           => $this->faker->randomElement(['draft', 'pending', 'approved', 'rejected']),
            'rejection_reason' => null,
            'submitted_at'     => null,
            'approved_at'      => null,
            'approved_by'      => null,
        ];
    }

    public function draft(): static
    {
        return $this->state(['status' => 'draft']);
    }

    public function pending(): static
    {
        return $this->state(['status' => 'pending', 'submitted_at' => now()]);
    }

    public function approved(): static
    {
        return $this->state(['status' => 'approved', 'submitted_at' => now(), 'approved_at' => now()]);
    }

    public function rejected(): static
    {
        return $this->state(['status' => 'rejected', 'submitted_at' => now()]);
    }
}
