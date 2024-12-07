<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(['id' => 1], [
            'name' => 'Default User',
            'email' => 'user@localhost.com',
            'password' => Hash::make('password')
        ]);
    }
}
