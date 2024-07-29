<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateExpenseRequest extends FormRequest
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
            'title'               => 'required|string',
            'description'         => 'sometimes|required|string',
            'amount'              => 'required|numeric',
            'date'                => 'required|date',
            'expense_category_id' => 'required|integer|exists:expense_categories,id',
            'budget_id'           => 'required|integer|exists:budgets,id',
        ];
    }
}
