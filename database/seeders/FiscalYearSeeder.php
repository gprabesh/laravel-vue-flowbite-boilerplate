<?php

namespace Database\Seeders;

use App\Models\AccountBook;
use App\Models\FiscalYear;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FiscalYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FiscalYear::updateOrCreate(['id' => 1, 'name' => '8182', 'code' => '8182'], [
            'start_date' => '2024-07-16',
            'end_date' => '2024-07-15',
            'nepali_start_date' => '2081-04-01',
            'nepali_end_date' => '2082-03-31',
        ]);
        AccountBook::updateOrCreate(['id' => 1], [
            'company_id' => 1,
            'fiscal_year_id' => 1
        ]);
        DB::table('users_account_books')->upsert(['user_id' => 1, 'account_book_id' => 1], [
            'account_book_id' => 1,
            'is_preferred' => 1
        ]);
    }
}
