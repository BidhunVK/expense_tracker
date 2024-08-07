<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
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
            'category_id' => 'required|integer',
            'amount' => 'required|numeric|gt:0',
            'description' => 'nullable|string',
           'date_of_expense' => [
                'required',
                'date',
                'before_or_equal:' . now()->format('Y-m-d'),
            ],
        ];
    }
}
