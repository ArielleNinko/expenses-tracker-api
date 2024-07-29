<?php

use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('expense', ExpenseController::class);
Route::resource('category', ExpenseCategoryController::class);
Route::resource('budget', BudgetController::class);