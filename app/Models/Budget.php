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
        'start_date',
        'end_date'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
