<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseCategoryRequest;
use App\Models\ExpenseCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class ExpenseCategoryController extends Controller
{
    /**
     * List of all categories
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $categories = ExpenseCategory::all();
        return response()->json([
            'success' => true,
            'message' => 'List of all expenses categories',
            'data' => $categories
        ], 200);
    }

    /**
     * Create a new category
     * 
     * @param ExpenseCategoryRequest $expenseCategoryRequest
     * @return JsonResponse
     */
    public function store(ExpenseCategoryRequest $expenseCategoryRequest): JsonResponse
    {
        DB::beginTransaction();

        try {
            $category = ExpenseCategory::create($expenseCategoryRequest->validated());

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Category stored successfully',
                'data' => $category
            ], 201);

        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'An error occurred while processing your request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a category by its id
     * 
     * @param ExpenseCategory $category
     * @return JsonResponse
     */
    public function show(ExpenseCategory $category) : JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Category successfully retrieved',
            'data'    => $category
        ], 200);
    }

    /**
     * Update an existing category
     * 
     * @param ExpenseCategory $category 
     * @param ExpenseCategoryRequest $expenseCategoryRequest
     * @return JsonResponse
     */
    public function update(ExpenseCategory $category, ExpenseCategoryRequest $expenseCategoryRequest): JsonResponse
    {
        DB::beginTransaction();

        try {

            $category->update($expenseCategoryRequest->validated());

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'data'    => $category
            ], 200);

        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Delete an existing category
     * 
     * @param ExpenseCategory $category
     * @return JsonResponse
     */
    public function destroy(ExpenseCategory $category): JsonResponse
    {
        DB::beginTransaction();

        try {

            $category->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully'
            ], 200);

        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
