<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'amount',
        'date',
        'expense_category_id',
        'budget_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'date'  => 'datetime:d-m-Y H:i:s'
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

}
