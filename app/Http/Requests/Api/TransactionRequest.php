<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
        return [
            'transaction_date' => 'required|date',
            'reference_no' => 'nullable|string',
            'description' => 'nullable|string|max:500',
            'transactions' => 'required|array|min:2',
            'transactions.*.account_id' => 'required|exists:accounts,id',
            'transactions.*.debit_amount' => 'nullable|numeric|min:0',
            'transactions.*.credit_amount' => 'nullable|numeric|min:0'
        ];
    }
    public function messages()
    {
        return [
            'transactions.min' => 'At least two transaction entries are required.',
            'transactions.*.accountID.exists' => 'Selected account is invalid.'
        ];
    }
}
