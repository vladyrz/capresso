<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeScholarship extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'program',
        'start_date',
        'end_date',
        'amount',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
