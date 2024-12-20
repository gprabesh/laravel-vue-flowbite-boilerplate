<?php

namespace App\Http\Controllers\Api;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\AccountRequest;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $company_id = $request->company_id;
        $accounts = Account::with(['category:id,name'])->select('id', 'name', 'is_cost_center', 'is_opening_balance_account', 'company_id', 'account_category_id')->where('company_id', $company_id)->where('is_opening_balance_account', 0)->get();
        return $this->jsonResponse(data: ['accounts' => $accounts]);
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
    public function store(AccountRequest $request)
    {
        $company_id = $request->company_id;
        $account = Account::create(array_merge($request->validated(), ['created_by' => Auth::id(), 'company_id' => $company_id]));
        return $this->jsonResponse(message: 'Account created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        return $this->jsonResponse(data: ['account' => $account]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AccountRequest $request, Account $account)
    {
        $account->update(array_merge($request->validated(), ['updated_by' => Auth::id()]));
        return $this->jsonResponse(message: 'Account Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        //
    }
}
