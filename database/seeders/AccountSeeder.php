<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Account::updateOrCreate([
            'id' => 1
        ], [
            'name' => 'Opening Balance',
            'account_category_id' => 2,
            'company_id' => 1,
            'is_opening_balance_account' => 1
        ]);
        Account::updateOrCreate([
            'id' => 2
        ], [
            'name' => 'Bank',
            'account_category_id' => 1,
            'company_id' => 1,
            'is_opening_balance_account' => 0
        ]);
        Account::updateOrCreate([
            'id' => 3
        ], [
            'name' => 'Cash',
            'account_category_id' => 1,
            'company_id' => 1,
            'is_opening_balance_account' => 0
        ]);
        Account::updateOrCreate([
            'id' => 4
        ], [
            'name' => 'Sale',
            'account_category_id' => 5,
            'company_id' => 1,
            'is_opening_balance_account' => 0
        ]);
        Account::updateOrCreate([
            'id' => 5
        ], [
            'name' => 'Salary',
            'account_category_id' => 6,
            'company_id' => 1,
            'is_opening_balance_account' => 0
        ]);
    }
}
