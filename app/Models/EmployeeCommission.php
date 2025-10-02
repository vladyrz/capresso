<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'concept',
        'amount',
        'earned_at',
    ];

    protected $casts = [
        'earned_at' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
