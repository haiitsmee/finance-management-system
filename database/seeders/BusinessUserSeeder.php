<?php

namespace Database\Seeders;

use App\Models\BusinessUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BusinessUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // yanto mengelola 2 divisi: Tralis dan Baja Kontruksi (1) dan Supplier (5)
            ['user_id' => 2, 'business_id' => 1],
            ['user_id' => 2, 'business_id' => 5],

            // rina mengelola 1 divisi: Makanan dan Minuman (2)
            ['user_id' => 3, 'business_id' => 2],

            // joko mengelola 2 divisi: Furniture dan Interior (3) dan Transportasi dan Alat Berat (4)
            ['user_id' => 4, 'business_id' => 3],
            ['user_id' => 4, 'business_id' => 4],

            // lilis mengelola 1 divisi: Tour dan Travel (6)
            ['user_id' => 5, 'business_id' => 6],
        ];

        foreach($data as $handle) {
            BusinessUser::create($handle);
        }
    }
}
