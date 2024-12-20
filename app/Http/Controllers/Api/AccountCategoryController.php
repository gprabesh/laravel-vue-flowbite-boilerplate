<?php

namespace App\Http\Controllers\Api;

use App\Models\AccountClass;
use Illuminate\Http\Request;
use App\Models\AccountCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AccountCategoryRequest;
use Illuminate\Support\Facades\Auth;

class AccountCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $account_categories = AccountCategory::with('accountClass', 'parent')->where('status', 1)->get();
        return $this->jsonResponse(data: ['account_categories' => $account_categories]);
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
    public function store(AccountCategoryRequest $request)
    {
        $account_category = AccountCategory::create(array_merge($request->validated(), ['created_by' => Auth::id()]));
        return $this->jsonResponse(message: 'Category created');
    }

    /**
     * Display the specified resource.
     */
    public function show(AccountCategory $accountCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccountCategory $accountCategory)
    {
        return $this->jsonResponse(data: ['account_category' => $accountCategory]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AccountCategoryRequest $request, AccountCategory $accountCategory)
    {
        $accountCategory->update(array_merge($request->validated(), ['updated_by' => Auth::id()]));
        return $this->jsonResponse(message: 'Category Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountCategory $accountCategory)
    {
        //
    }

    public function getParentCategories()
    {
        $parent_categories =  AccountCategory::whereNull('parent_id')->get();
        return $this->jsonResponse(data: ['parent_categories' => $parent_categories]);
    }

    public function getAccountClasses()
    {
        $account_classes = AccountClass::all();
        return $this->jsonResponse(data: ['account_classes' => $account_classes]);
    }
}
