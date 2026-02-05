<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'username' => 'superadmin',
                'password' => 'superadmin123',
                'slug' => Str::slug('superadmin'),
                'role' => 'superadmin'
            ],
            [
                'username' => 'yanto',
                'password' => 'admin123',
                'slug' => Str::slug('yanto'),
                'role' => 'admin',
            ],
            [
                'username' => 'rina',
                'password' => 'admin123',
                'slug' => Str::slug('rina'),
                'role' => 'admin',
            ],
            [
                'username' => 'joko',
                'password' => 'admin123',
                'slug' => Str::slug('joko'),
                'role' => 'admin',
            ],
            [
                'username' => 'lilis',
                'password' => 'admin123',
                'slug' => Str::slug('lilis'),
                'role' => 'admin',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
