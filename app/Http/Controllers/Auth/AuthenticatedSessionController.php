<?php

namespace App\Http\Controllers\Auth;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function me(Request $request)
    {
        $user = DB::table('users as u')
            ->leftJoin('users_account_books as uab', function ($join) {
                $join->on('uab.user_id', '=', 'u.id')
                    ->where('uab.is_preferred', '=', 1);
            })
            ->leftJoin('account_books as ab', 'ab.id', '=', 'uab.account_book_id')
            ->where('u.id', '=', Auth::id())
            ->select(
                'u.id',
                'u.name',
                'u.email',
                'uab.account_book_id',
                'ab.company_id',
                'u.created_at',
                'u.updated_at'
            )
            ->first();

        $user_account_books = DB::table('users_account_books as uab')
            ->join('account_books as ab', 'uab.account_book_id', '=', 'ab.id')
            ->join('companies as c', 'c.id', '=', 'ab.company_id')
            ->join('fiscal_years as fy', 'ab.fiscal_year_id', '=', 'fy.id')
            ->where('uab.user_id', Auth::id())
            ->select(
                'c.name as company',
                'fy.name as fiscal_year',
                'ab.id as account_book_id',
                'uab.is_preferred as is_preferred'
            )
            ->get();
        $user->account_books = $user_account_books;

        return $this->jsonResponse(data: ['user' => $user]);
    }
}
