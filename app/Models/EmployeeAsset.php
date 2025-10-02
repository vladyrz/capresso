<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'type',
        'brand',
        'model',
        'serial',
        'assigned_at',
        'returned_at',
    ];

    protected $casts = [
        'assigned_at' => 'date',
        'returned_at' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
