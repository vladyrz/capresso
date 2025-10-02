<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeUniform extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'delivered_at',
        'details',
    ];

    protected $casts = [
        'delivered_at' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
