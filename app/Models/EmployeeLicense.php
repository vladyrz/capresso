<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLicense extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'type',
        'category',
        'number',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
