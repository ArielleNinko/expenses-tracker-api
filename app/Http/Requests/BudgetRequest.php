<?php

namespace App\Http\Requests;

use App\Enums\BudgetPeriod;
use App\Enums\Currency;
use Illuminate\Foundation\Http\FormRequest;

class BudgetRequest extends FormRequest
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
            'amount'     => 'required|numeric',
            'period'     => 'required|string|in:'. BudgetPeriod::getCases(),
            'currency'   => 'required|string|in:'. Currency::getCases(),
            'start_date' => 'sometimes|required|date',
            'end_date'   => 'sometimes|required|date',
        ];
    }
}
