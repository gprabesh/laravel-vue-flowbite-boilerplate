<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        $account_book_id = 1;
        $company_id = 1;
        try {
            // Begin database transaction
            DB::beginTransaction();

            // Validate total debit and credit amounts
            $transactions = $request->input('transactions');
            $totalDebit = array_sum(array_column($transactions, 'debitAmount'));
            $totalCredit = array_sum(array_column($transactions, 'creditAmount'));

            if ($totalDebit !== $totalCredit) {
                return response()->json([
                    'message' => 'Total debit and credit amounts must be equal'
                ], 422);
            }

            // Create main transaction record
            $transaction = Transaction::create([
                'transaction_date' => $request->input('transactionDate'),
                'description' => $request->input('description'),
                'transaction_amount' => $totalDebit,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            // Create transaction details
            foreach ($transactions as $detail) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'account_id' => $detail['accountID'],
                    'debit_amount' => $detail['debitAmount'] ?? 0,
                    'credit_amount' => $detail['creditAmount'] ?? 0,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
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
        //
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
