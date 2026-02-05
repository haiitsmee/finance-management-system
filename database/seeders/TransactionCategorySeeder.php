<?php

namespace Database\Seeders;

use App\Models\TransactionCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactionCategories = [
            [
                'title' => 'Penjualan Produk',
                'tags' => 'pemasukan',
            ],
            [
                'title' => 'Pembelian Bahan',
                'tags' => 'pengeluaran',
            ],
            [
                'title' => 'Sewa Toko',
                'tags' => 'pengeluaran',
            ],
            [
                'title' => 'Layanan Servis',
                'tags' => 'pemasukan',
            ],
        ];

        foreach($transactionCategories as $category) {
            TransactionCategory::create($category);
        }
    }
}
