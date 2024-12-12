<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\TransactionRequest;

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
            ->groupBy('t.id')
            ->orderBy('t.id', 'desc')
            ->select(
                't.id',
                't.transaction_date',
                't.reference_no',
                't.description',
                DB::raw('GROUP_CONCAT(a.name) as accounts'),
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
            // Begin database transaction
            DB::beginTransaction();

            // Validate total debit and credit amounts
            $transactions = $request->input('transactions');
            $totalDebit = array_sum(array_column($transactions, 'debit_amount'));
            $totalCredit = array_sum(array_column($transactions, 'credit_amount'));

            if ($totalDebit !== $totalCredit) {
                return response()->json([
                    'message' => 'Total debit and credit amounts must be equal'
                ], 422);
            }

            // Create main transaction record
            $transaction = Transaction::create([
                'transaction_date' => $request->input('transaction_date'),
                'reference_no' => $request->input('reference_no'),
                'description' => $request->input('description'),
                'transaction_amount' => $totalDebit,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'account_book_id' => $account_book_id,
                'company_id' => $company_id
            ]);

            // Create transaction details
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

            // Commit the database transaction
            DB::commit();

            return response()->json([
                'message' => 'Transaction created successfully',
                'transaction' => $transaction
            ], 201);
        } catch (\Exception $e) {
            // Rollback in case of error
            DB::rollBack();

            return response()->json([
                'message' => 'Error creating transaction',
                'error' => $e->getMessage()
            ], 500);
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
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
