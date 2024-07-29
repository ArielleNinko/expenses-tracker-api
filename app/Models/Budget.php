<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'period',
        'currency',
        'start_date',
        'end_date'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'start_date'  => 'datetime:d-m-Y H:i:s',
        'end_date'    => 'datetime:d-m-Y H:i:s'
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

}
