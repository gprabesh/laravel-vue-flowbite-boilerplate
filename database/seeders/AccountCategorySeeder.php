<?php

namespace Database\Seeders;

use App\Models\AccountCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AccountCategory::updateOrCreate(['id' => 1, 'name' => 'Current Assets', 'account_class_id' => 1]);
        AccountCategory::updateOrCreate(['id' => 2, 'name' => 'Current Liabilities', 'account_class_id' => 2]);
        AccountCategory::updateOrCreate(['id' => 3, 'name' => 'Sundry Debtors', 'account_class_id' => 1]);
        AccountCategory::updateOrCreate(['id' => 4, 'name' => 'Sundry Creditors', 'account_class_id' => 2]);
        AccountCategory::updateOrCreate(['id' => 5, 'name' => 'Income', 'account_class_id' => 4]);
        AccountCategory::updateOrCreate(['id' => 6, 'name' => 'Expenses', 'account_class_id' => 5]);
    }
}
