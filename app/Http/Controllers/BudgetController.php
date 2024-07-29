<?php

namespace App\Http\Controllers;

use App\Http\Requests\BudgetRequest;
use App\Http\Requests\UpdateBudgetRequest;
use App\Models\Budget;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class BudgetController extends Controller
{
    /**
     * List of all budgets
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $budgets = Budget::all();
        return response()->json([
            'success' => true,
            'message' => 'List of all budgets',
            'data' => $budgets
        ], 200);
    }

    /**
     * Create a new budget
     * 
     * @param BudgetRequest $budgetRequest
     * @return JsonResponse
     */
    public function store(BudgetRequest $budgetRequest): JsonResponse
    {
        DB::beginTransaction();

        try {
            $budget = Budget::create($budgetRequest->validated());

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Budget stored successfully',
                'data' => $budget
            ], 201);

        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Get a budget by its id
     * 
     * @param Budget $budget
     * @return JsonResponse
     */
    public function show(Budget $budget) : JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Budget successfully retrieved',
            'data'    => $budget
        ], 200);
    }

    /**
     * Update an existing category
     * 
     * @param Budget $budget
     * @param UpdateBudgetRequest $updateBudgetRequest
     * @return JsonResponse
     */
    public function update(Budget $budget, UpdateBudgetRequest $updateBudgetRequest): JsonResponse
    {
        DB::beginTransaction();

        try {

            $budget->update($updateBudgetRequest->validated());

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Budget updated successfully',
                'data'    => $budget
            ], 200);

        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Delete an existing category
     * 
     * @param Budget $budget
     * @return JsonResponse
     */
    public function destroy(Budget $budget): JsonResponse
    {
        DB::beginTransaction();

        try {

            $budget->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Budget deleted successfully'
            ], 200);

        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
