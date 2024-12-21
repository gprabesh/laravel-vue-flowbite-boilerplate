<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->method() == 'POST') {
            return [
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|confirmed',
                'account_books' => 'required|array'
            ];
        } else {
            return [
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email,' . $this->user->id,
                'password' => 'nullable|min:8|confirmed',
                'account_books' => 'required|array',
            ];
        }
    }
}
