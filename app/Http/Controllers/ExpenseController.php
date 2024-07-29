<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Budget;
use App\Models\Expense;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class ExpenseController extends Controller
{
    /**
     * List of all expenses
     */
    public function index()
    {
        $expenses = Expense::all();

        return response()->json([
            'success' => true,
            'message' => 'List of all expenses',
            'data'    => $expenses
        ], 200);
    }

    /**
     * Create a new expense
     * 
     * @param CreateExpenseRequest $createExpenseRequest
     * @return JsonResponse
     */
    public function store(CreateExpenseRequest $createExpenseRequest): JsonResponse
    {
        DB::beginTransaction();

        try {

            $expense = Expense::create($createExpenseRequest->validated());

            $budget = Budget::findOrFail($createExpenseRequest->validated('budget_id'));

            // Calculate the remaining amount
            $remainingAmount = $budget->amount - $expense->amount;

            if ($remainingAmount < 0) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Expense exceeds the budget amount'
                ], 400);
            }

            $budget->update(['amount' => $remainingAmount]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Expense added successfully",
                'data' => $expense
            ], 201);

        } catch (Throwable $e) {
            DB::rollBack();
            
            throw $e;
        }
    }

    /**
     * Get an expense by its id
     * 
     * @param Expense $expense
     * @return JsonResponse
     */
    public function show(Expense $expense) : JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => "Expense successfully retrieved",
            'data'    => $expense
        ], 200);
    }

    /**
     * Update an existing expense
     */
    public function update(Expense $expense, UpdateExpenseRequest $updateExpenseRequest)
    {
        DB::beginTransaction();

        try {

            $existingAmount = $expense->amount;

            $validated = $updateExpenseRequest->validated();

            $expense->update($validated);

            if (array_key_exists('amount', $validated) && $validated['amount'] != $existingAmount) {
                $budget = Budget::findOrFail($expense->budget_id);

                // Calculate the difference et update the budget
                $amountDifference = $existingAmount - $validated['amount'];
                $remainingAmount = $budget->amount + $amountDifference;

                if ($remainingAmount < 0) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Expense update exceeds the budget amount'
                    ], 400);
                }

                $budget->update(['amount' => $remainingAmount]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Expense updated successfully",
                'data' => $expense
            ], 200);

        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Delete an existing expense
     * 
     * @param Expense $expense
     * @return JsonResponse
     */
    public function destroy(Expense $expense): JsonResponse
    {
        DB::beginTransaction();

        try {

            $expense->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Expense deleted successfully"
            ], 200);

        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
