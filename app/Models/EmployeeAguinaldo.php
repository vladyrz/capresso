<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAguinaldo extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'year',
        'amount',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
