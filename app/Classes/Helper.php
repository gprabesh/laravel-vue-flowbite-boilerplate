<?php

namespace App\Classes;

use Illuminate\Support\Facades\DB;

class Helper
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function generateVoucherNumber($account_book_id)
    {
        $latest_voucher = DB::table('transactions')->where('account_book_id', $account_book_id)->where('voucher_type', 'JV')->whereNotNull('voucher_no')->latest()->first();
        if (!empty($latest_voucher)) {
            return $latest_voucher->voucher_no + 1;
        }
        return 1;
    }
}
