<?php

namespace Database\Factories;

use App\Models\Debt;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Debt>
 */
class DebtFactory extends Factory
{
    protected $model = \App\Models\Debt::class;

    public function definition(): array
    {
        return [
            'business_id'   => $this->faker->numberBetween(1, 6),
            'category'      => $this->faker->randomElement(['Pembelian Barang', 'Lainnya']),
            'supplier'      => $this->faker->company,  
            'amount'        => $this->faker->numberBetween(100000, 5000000),
            'due_date'      => Carbon::now()->addDays(rand(7, 60)),
            'date'          => Carbon::now()->subDays(rand(0, 30)),
            'is_paid'       => $this->faker->boolean(30), // 30% chance lunas
            'description'   => $this->faker->sentence(),
            'created_at'    => now(),
            'updated_at'    => now(),
        ];
    }
}
