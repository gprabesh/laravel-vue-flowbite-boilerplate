<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AccountCategoryRequest extends FormRequest
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
                'name' => 'required|string|max:255',
                'account_class_id' => 'required|exists:account_classes,id',
                'parent_id' => [
                    'nullable',
                    'exists:account_categories,id'
                ]
            ];
        } else {
            return [
                'name' => 'required|string|max:255',
                'account_class_id' => 'required|exists:account_classes,id',
                'parent_id' => [
                    'nullable',
                    'exists:account_categories,id',
                    Rule::notIn([$this->account_category->id])
                ]
            ];
        }
    }
}
