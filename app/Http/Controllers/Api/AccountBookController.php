<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AccountBookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $account_books = DB::table('account_books as ab')
            ->join('fiscal_years as fy', 'ab.fiscal_year_id', '=', 'fy.id')
            ->join('companies as c', 'ab.company_id', '=', 'c.id')
            ->select(
                'ab.id',
                DB::raw('CONCAT(c.name, " | ", fy.code) as name'),
                'c.name as company',
                'fy.code as fiscal_year',
                'fy.start_date',
                'fy.end_date',
                'ab.company_id'
            )
            ->get();
        return $this->jsonResponse(data: ['account_books' => $account_books]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
