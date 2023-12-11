<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                "name" => "Seller",
                "email" => "syaripmasud@gmail.com",
                "password" => "password",
                "role" => 'seller'
            ],
            [
                "name" => "Buyer",
                "email" => "masudsyarip808@gmail.com",
                "password" => "password",
                "role" => 'buyer'
            ]
        ];
        foreach ($users as $user) {
            User::create([
                'email' => $user['email'],
                'name' => $user['name'],
                'role' => $user['role'],
                'password' => Hash::make($user['password'])
            ]);
        }
    }
}
