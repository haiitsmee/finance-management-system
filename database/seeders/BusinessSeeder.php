<?php

namespace Database\Seeders;

use App\Models\Business;
use Illuminate\Database\Seeder;
use Str;

class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = [
            [
                'name' => 'Pusat',
                'slug' => Str::slug('Pusat'),
                'responsible' => 'Super Admin',
                'contact_responsible' => '',
                'employee_headcount' => 0,
                'description' => 'Divisi yang mengatur manajemen pusat.',
                'is_visible' => false
            ],
            [
                'name' => 'Tralis dan Baja Kontruksi',
                'slug' => Str::slug('Tralis dan Baja Kontruksi'),
                'responsible' => 'Pak Yanto',
                'contact_responsible' => '+62892327521',
                'employee_headcount' => 5,
                'description' => 'Divisi yang bergerak pada bidang tralis dan baja kontruksi.'
            ],
            [
                'name' => 'Makanan dan Minuman',
                'slug' => Str::slug('Makanan dan Minuman'),
                'responsible' => 'Bu Rina',
                'contact_responsible' => '+62892123456',
                'employee_headcount' => 8,
                'description' => 'Divisi yang bergerak di bidang konsumsi makanan dan minuman.'
            ],
            [
                'name' => 'Furniture dan Interior',
                'slug' => Str::slug('Furniture dan Interior'),
                'responsible' => 'Pak Joko',
                'contact_responsible' => '+62892789123',
                'employee_headcount' => 6,
                'description' => 'Divisi untuk pengadaan furniture dan desain interior.'
            ],
            [
                'name' => 'Transportasi dan Alat Berat',
                'slug' => Str::slug('Transportasi dan Alat Berat'),
                'responsible' => 'Bu Sari',
                'contact_responsible' => '+62892345678',
                'employee_headcount' => 4,
                'description' => 'Divisi penyedia kendaraan operasional dan alat berat.'
            ],
            [
                'name' => 'Tour dan Travel',
                'slug' => Str::slug('Tour dan Travel'),
                'responsible' => 'Bu Lilis',
                'contact_responsible' => '+62892765432',
                'employee_headcount' => 2,
                'description' => 'Divisi yang mengatur perjalanan dinas dan kebutuhan travel.'
            ],  
        ];

        foreach ($divisions as $division) {
            Business::create($division);
        }
    }
}
