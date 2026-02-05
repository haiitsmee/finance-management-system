<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactions = [
            [
                'business_id' => 1,
                'transaction_category_id' => 1,
                'product' => 'Laptop Asus A455L',
                'price' => 5500000,
                'product_quantity' => 1,
                'total' => 5500000,
                'transaction_date' => now(),
                'description' => 'Penjualan laptop kepada pelanggan',
                'status' => 'lunas',
                'image' => 'laptop_asus.jpg',
            ],
            [
                'business_id' => 2,
                'transaction_category_id' => 2,
                'product' => 'Kertas A4',
                'price' => 45000,
                'product_quantity' => 10,
                'total' => 450000,
                'transaction_date' => now()->subDays(2),
                'description' => 'Pembelian kertas untuk keperluan administrasi',
                'status' => 'lunas',
                'image' => 'kertas_a4.jpg',
            ],
            [
                'business_id' => 1,
                'transaction_category_id' => 1,
                'product' => 'Printer Canon IP2770',
                'price' => 1200000,
                'product_quantity' => 2,
                'total' => 2400000,
                'transaction_date' => now()->subDays(3),
                'description' => 'Penjualan printer ke pelanggan toko',
                'status' => 'lunas',
                'image' => 'printer_canon.jpg',
            ],
            [
                'business_id' => 2,
                'transaction_category_id' => 1,
                'product' => 'Tinta Printer Hitam',
                'price' => 85000,
                'product_quantity' => 5,
                'total' => 425000,
                'transaction_date' => now()->subDays(4),
                'description' => 'Pembelian tinta printer untuk stok',
                'status' => 'lunas',
                'image' => 'tinta_printer.jpg',
            ],
            [
                'business_id' => 3,
                'transaction_category_id' => 1,
                'product' => 'Flashdisk 32GB',
                'price' => 75000,
                'product_quantity' => 15,
                'total' => 1125000,
                'transaction_date' => now()->subDays(5),
                'description' => 'Penjualan flashdisk di toko komputer',
                'status' => 'lunas',
                'image' => 'flashdisk.jpg',
            ],
            [
                'business_id' => 1,
                'transaction_category_id' => 1,
                'product' => 'Mouse Logitech M170',
                'price' => 120000,
                'product_quantity' => 8,
                'total' => 960000,
                'transaction_date' => now()->subDays(6),
                'description' => 'Pembelian mouse untuk persediaan',
                'status' => 'lunas',
                'image' => 'mouse_logitech.jpg',
            ],
            [
                'business_id' => 2,
                'transaction_category_id' => 1,
                'product' => 'Keyboard Mechanical',
                'price' => 450000,
                'product_quantity' => 3,
                'total' => 1350000,
                'transaction_date' => now()->subDays(7),
                'description' => 'Penjualan keyboard kepada pelanggan gamer',
                'status' => 'lunas',
                'image' => 'keyboard_mechanical.jpg',
            ],
            [
                'business_id' => 3,
                'transaction_category_id' => 1,
                'product' => 'Kabel HDMI 2 Meter',
                'price' => 50000,
                'product_quantity' => 6,
                'total' => 300000,
                'transaction_date' => now()->subDays(8),
                'description' => 'Pembelian kabel HDMI untuk stok',
                'status' => 'lunas',
                'image' => 'kabel_hdmi.jpg',
            ],
            [
                'business_id' => 1,
                'transaction_category_id' => 1,
                'product' => 'Monitor Samsung 24 Inch',
                'price' => 1750000,
                'product_quantity' => 2,
                'total' => 3500000,
                'transaction_date' => now()->subDays(9),
                'description' => 'Penjualan monitor kepada pelanggan',
                'status' => 'lunas',
                'image' => 'monitor_samsung.jpg',
            ],
            [
                'business_id' => 2,
                'transaction_category_id' => 1,
                'product' => 'Kertas Foto A4',
                'price' => 120000,
                'product_quantity' => 5,
                'total' => 600000,
                'transaction_date' => now()->subDays(10),
                'description' => 'Pembelian kertas foto untuk cetak dokumen',
                'status' => 'lunas',
                'image' => 'kertas_foto.jpg',
            ],
        ];

        foreach ($transactions as $transaction) {
            Transaction::create($transaction);
        }
    }
}
