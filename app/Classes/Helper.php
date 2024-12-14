<?php

namespace App\Classes;

use Carbon\Carbon;
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

    public static function getOpeningBalance($account_id, $account_book_id, $date)
    {
        $fiscal_start_date = self::getFiscalYearStartDate($account_book_id);
        $opening_balance = 0;
        if (Carbon::parse($fiscal_start_date)->lt(Carbon::parse($date))) {
            $opening_balance = DB::table('transaction_details')
                ->where('account_id', $account_id)
                ->where('account_book_id', $account_book_id)
                ->where('transaction_date', '<', $date)
                ->select(DB::raw('SUM(debit_amount) - SUM(credit_amount) as balance'))
                ->value('balance') ?? 0;
        } elseif (Carbon::parse($fiscal_start_date)->gte(Carbon::parse($date))) {
            $opening_balance = DB::table('transaction_details as td')
                ->join('transactions as t', 'td.transaction_id', '=', 't.id')
                ->where('account_id', $account_id)
                ->where('td.account_book_id', $account_book_id)
                ->where('t.is_opening_balance_transaction', 1)
                ->select(DB::raw('SUM(debit_amount) - SUM(credit_amount) as balance'))
                ->value('balance') ?? 0;
        }
        return $opening_balance;
    }
    public static function getFiscalYearStartDate($account_book_id)
    {
        return DB::table('account_books as ab')
            ->join('fiscal_years as fy', 'ab.fiscal_year_id', '=', 'fy.id')
            ->where('ab.id', $account_book_id)
            ->select(
                'fy.id',
                'fy.start_date',
                'fy.end_date'
            )
            ->first()->start_date;
    }
    public static function getLedgerData($account_book_id, $from, $to, $account_id)
    {
        $from = Carbon::parse($from);
        $to = Carbon::parse($to);
        $opening_balance = Helper::getOpeningBalance($account_id, $account_book_id, $from);
        $opening_balance_object = (object)[
            'id' => 0,
            'transaction_date' => null,
            'voucher_no' => 'Opening Balance',
            'reference_no' => null,
            'description' => null,
            'created_by' => null,
            'debit_amount' => $opening_balance > 0 ? abs($opening_balance) : 0,
            'credit_amount' => $opening_balance < 0 ? abs($opening_balance) : 0,
            'balance' => number_format(abs($opening_balance), 2),
            'balance_type' => $opening_balance >= 0 ? "DR" : "CR"
        ];
        $results = DB::table('transaction_details as td')
            ->join('accounts as a', 'a.id', '=', 'td.account_id')
            ->join('transactions as t', 't.id', '=', 'td.transaction_id')
            ->join('users as u', 't.created_by', '=', 'u.id')
            ->where('td.account_id', $account_id)
            ->when($from && $to, function ($query) use ($from, $to) {
                return $query->whereBetween('t.transaction_date', [$from, $to]);
            })
            ->where('t.is_opening_balance_transaction', 0)
            ->orderBy('t.transaction_date')
            ->orderBy('t.voucher_no', 'asc')
            ->select(
                't.id',
                't.transaction_date',
                DB::raw('CONCAT(t.voucher_type, LPAD(t.voucher_no, 5, "0")) as voucher_no'),
                't.reference_no',
                't.description',
                'u.name as created_by',
                'td.debit_amount',
                'td.credit_amount',
                DB::raw('0 as balance'),
                DB::raw("'DR' as balance_type")
            )
            ->get();
        foreach ($results as $key => $value) {
            $opening_balance  = round($opening_balance + $value->debit_amount - $value->credit_amount, 2);
            $value->balance_type = $opening_balance >= 0 ? 'DR' : 'CR';
            $value->balance = number_format(abs($opening_balance), 2);
        }
        $results->prepend($opening_balance_object);
        return $results;
    }
}
