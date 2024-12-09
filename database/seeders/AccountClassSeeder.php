<?php

namespace Database\Seeders;

use App\Models\AccountClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AccountClass::updateOrCreate(['id' => 1, 'name' => 'Assets', 'debit' => 1, 'credit' => 0, 'display_order' => 1]);
        AccountClass::updateOrCreate(['id' => 2, 'name' => 'Liabilities', 'debit' => 0, 'credit' => 1, 'display_order' => 2]);
        AccountClass::updateOrCreate(['id' => 3, 'name' => 'Capital', 'debit' => 0, 'credit' => 1, 'display_order' => 3]);
        AccountClass::updateOrCreate(['id' => 4, 'name' => 'Income', 'debit' => 0, 'credit' => 1, 'display_order' => 4]);
        AccountClass::updateOrCreate(['id' => 5, 'name' => 'Expenses', 'debit' => 1, 'credit' => 0, 'display_order' => 5]);
    }
}
