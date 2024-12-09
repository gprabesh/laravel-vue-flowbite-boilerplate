<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(CompanySeeder::class);
        $this->call(FiscalYearSeeder::class);
        $this->call(AccountClassSeeder::class);
        $this->call(AccountCategorySeeder::class);
        $this->call(AccountSeeder::class);

    }
}
