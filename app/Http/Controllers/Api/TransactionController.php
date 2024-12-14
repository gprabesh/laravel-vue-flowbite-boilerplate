<?php

namespace App\Http\Controllers\Api;

use App\Classes\Helper;
use App\Exceptions\CustomException;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\TransactionRequest;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $account_book_id = $request->account_book_id;
        $transactions = DB::table('transactions as t')
            ->join('transaction_details as td', 'td.transaction_id', '=', 't.id')
            ->join('accounts as a', 'a.id', '=', 'td.account_id')
            ->leftJoin('users as u', 'u.id', '=', 't.created_by')
            ->where('t.account_book_id', $account_book_id)
            ->when(isset($request->from) && isset($request->to), function ($query) use ($request) {
                return $query->whereBetween('t.transaction_date', [$request->from, $request->to]);
            })
            ->groupBy('t.id')
            ->orderBy('t.id', 'desc')
            ->select(
                't.id',
                't.transaction_date',
                't.reference_no',
                't.description',
                DB::raw('GROUP_CONCAT(a.name) as accounts'),
                DB::raw('concat(t.voucher_type, LPAD(t.voucher_no, 5, "0")) as voucher_no'),
                't.transaction_amount',
                'u.name as created_by'
            )
            ->get();
        return $this->jsonResponse(data: ['transactions' => $transactions]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionRequest $request)
    {
        $account_book_id = $request->account_book_id;
        $company_id = $request->company_id;
        try {
            DB::beginTransaction();

            $transactions = $request->input('transactions');
            $totalDebit = array_sum(array_column($transactions, 'debit_amount'));
            $totalCredit = array_sum(array_column($transactions, 'credit_amount'));

            if ($totalDebit !== $totalCredit) {
                throw new CustomException('Total debit and credit amounts must be equal');
            }

            $transaction = Transaction::create([
                'transaction_date' => $request->input('transaction_date'),
                'reference_no' => $request->input('reference_no'),
                'description' => $request->input('description'),
                'transaction_amount' => $totalDebit,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'account_book_id' => $account_book_id,
                'company_id' => $company_id,
                'voucher_no' => Helper::generateVoucherNumber($account_book_id)
            ]);

            foreach ($transactions as $detail) {
                TransactionDetail::create([
                    'transaction_date' => $request->input('transaction_date'),
                    'transaction_id' => $transaction->id,
                    'account_id' => $detail['account_id'],
                    'debit_amount' => $detail['debit_amount'] ?? 0,
                    'credit_amount' => $detail['credit_amount'] ?? 0,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                    'account_book_id' => $account_book_id,
                    'company_id' => $company_id
                ]);
            }
            DB::commit();

            return $this->jsonResponse(status: 200, message: 'Transaction created successfully');
        } catch (CustomException $ce) {
            DB::rollBack();
            Log::error($ce);
            return $this->jsonResponse(status: 500, message: $ce->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return $this->jsonResponse(status: 500, message: 'Error creating transaction');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        $transaction = Transaction::with('transactions', 'transactions.account')->where('id', $transaction->id)->first();
        return $this->jsonResponse(data: ['transaction' => $transaction]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransactionRequest $request, Transaction $transaction)
    {
        $account_book_id = $request->account_book_id;
        $company_id = $request->company_id;
        try {
            DB::beginTransaction();

            $transactions = $request->input('transactions');
            $deletedTransactions = $request->input('deleted_transaction_details');
            $totalDebit = array_sum(array_column($transactions, 'debit_amount'));
            $totalCredit = array_sum(array_column($transactions, 'credit_amount'));

            if ($totalDebit !== $totalCredit) {
                throw new CustomException('Total debit and credit amounts must be equal');
            }

            $updated = $transaction->update([
                'transaction_date' => $request->input('transaction_date'),
                'reference_no' => $request->input('reference_no'),
                'description' => $request->input('description'),
                'transaction_amount' => $totalDebit,
                'updated_by' => Auth::id(),
            ]);

            foreach ($transactions as $detail) {
                if ($detail['id'] > 0) {
                    TransactionDetail::where('id', $detail['id'])->first()->update([
                        'transaction_date' => $request->input('transaction_date'),
                        'account_id' => $detail['account_id'],
                        'debit_amount' => $detail['debit_amount'] ?? 0,
                        'credit_amount' => $detail['credit_amount'] ?? 0,
                        'updated_by' => Auth::id(),
                    ]);
                } else {
                    TransactionDetail::create([
                        'transaction_date' => $request->input('transaction_date'),
                        'transaction_id' => $transaction->id,
                        'account_id' => $detail['account_id'],
                        'debit_amount' => $detail['debit_amount'] ?? 0,
                        'credit_amount' => $detail['credit_amount'] ?? 0,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        'account_book_id' => $account_book_id,
                        'company_id' => $company_id
                    ]);
                }
            }
            foreach ($deletedTransactions as $key => $value) {
                TransactionDetail::where('id', $value['id'])->first()->delete();
            }
            DB::commit();

            return $this->jsonResponse(status: 200, message: 'Transaction updated successfully');
        } catch (CustomException $ce) {
            DB::rollBack();
            Log::error($ce);
            return $this->jsonResponse(status: 500, message: $ce->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return $this->jsonResponse(status: 500, message: 'Error updating transaction');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }

    public function getPrintData($id)
    {
        $print_data = DB::table('transactions as t')
            ->join('transaction_details as td', 'td.transaction_id', '=', 't.id')
            ->join('accounts as a', 'td.account_id', '=', 'a.id')
            ->join('companies as c', 't.company_id', '=', 'c.id')
            ->join('users as u', 't.created_by', '=', 'u.id')
            ->where('t.id', $id)
            ->groupBy('t.id')
            ->select(
                't.id',
                'c.name as company',
                DB::raw('concat(t.voucher_type, LPAD(t.voucher_no, 5, "0")) as voucher_no'),
                't.description',
                't.reference_no',
                't.transaction_date',
                'u.name as created_by',
                DB::raw('SUM(td.debit_amount) as total_debit'),
                DB::raw('SUM(td.credit_amount) as total_credit'),
                DB::raw('JSON_ARRAYAGG(
                            JSON_OBJECT(
                                "id", td.id,
                                "account", a.name,
                                "debit_amount", td.debit_amount,
                                "credit_amount", td.credit_amount
                            )
                        ) AS transaction_details')
            )
            ->first();
        return $this->jsonResponse(data: ['print_data' => $print_data]);
    }
}
