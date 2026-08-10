<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * UserSeeder
 */
class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'user',
            'password' => Hash::make('test'),
            'email' => 'test@mail.com',
        ]);

        User::create([
            'name' => 'user2',
            'password' => Hash::make('test'),
            'email' => 'test2@mail.com',
        ]);

        User::create([
            'name' => 'user3',
            'password' => Hash::make('test'),
            'email' => 'test3@mail.com',
        ]);
    }
}
