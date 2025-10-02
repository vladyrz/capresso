<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeBonus extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'concept',
        'amount',
        'granted_at',
    ];

    protected $casts = [
        'granted_at' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
