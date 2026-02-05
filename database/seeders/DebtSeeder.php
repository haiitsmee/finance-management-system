<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Debt;

class DebtSeeder extends Seeder
{
    public function run(): void
    {
        // buat 20 data
        Debt::factory()->count(20)->create();
    }
}
