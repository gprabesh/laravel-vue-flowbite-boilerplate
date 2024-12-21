<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\UserRequest;
use App\Models\UserAccountBook;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::select('id', 'name', 'email')->get();
        return $this->jsonResponse(data: ['users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $account_books = $validated['account_books'];
            $account_book_count = count($account_books);
            unset($validated['account_books'], $validated['password_confirmation']);
            $validated['created_by'] = Auth::id();
            $validated['password'] = Hash::make($validated['password']);

            $user = User::create($validated);
            foreach ($account_books as $key => $value) {
                if ($key + 1 == $account_book_count) {
                    UserAccountBook::create(['user_id' => $user->id, 'account_book_id' => $value['id'], 'is_preferred' => 1]);
                } else {
                    UserAccountBook::create(['user_id' => $user->id, 'account_book_id' => $value['id']]);
                }
            }

            DB::commit();
            return $this->jsonResponse(message: 'User created');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            return $this->jsonResponse(message: 'Failed to create user', status: 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->account_books = DB::table('users_account_books')->where('user_id', $user->id)->pluck('account_book_id');
        return $this->jsonResponse(data: ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $account_books = $validated['account_books'];
            $account_book_count = count($account_books);
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            if (isset($validated['password']) && !empty($validated['password'])) {
                $user->password = Hash::make($validated['email']);
            }
            $user->updated_by = Auth::id();
            $user->update();
            UserAccountBook::where('user_id', $user->id)->delete();
            foreach ($account_books as $key => $value) {
                if ($key + 1 == $account_book_count) {
                    UserAccountBook::create(['user_id' => $user->id, 'account_book_id' => $value['id'], 'is_preferred' => 1]);
                } else {
                    UserAccountBook::create(['user_id' => $user->id, 'account_book_id' => $value['id']]);
                }
            }
            DB::commit();
            return $this->jsonResponse(message: 'User updated');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            return $this->jsonResponse(message: 'Failed to update user', status: 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
