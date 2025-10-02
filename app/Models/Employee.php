<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'department_id',
        'manager_id',
        'name',
        'cedula',
        'email',
        'phone',
        'hired_at',
        'birth_date',
        'blood_type',
        'passport_number',
        'passport_expires_at',
        'us_visa_expires_at',
        'country',
        'address',
        'academic_degree',
        'is_payroll',
        'is_outsourcing',
        'position',
        'main_function',
        'gross_salary_amount',
        'gross_salary_currency',
        'gross_salary_amount_local',
        'gross_salary_amount_usd',
        'gross_salary_base_currency',
        'social_chargers',
        'has_car_license',
        'has_motorcycle_license',
    ];

    protected $casts = [
        'hired_at'                  => 'date',
        'birth_date'                => 'date',
        'passport_expires_at'       => 'date',
        'us_visa_expires_at'        => 'date',
        'is_payroll'                => 'boolean',
        'is_outsourcing'            => 'boolean',
        'has_car_license'           => 'boolean',
        'has_motorcycle_license'    => 'boolean',
    ];

    // Relaciones organizacionales
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }
    public function reports()
    {
        return $this->hasMany(Employee::class, 'manager_id');
    }

    // Subtablas
    public function licenses()
    {
        return $this->hasMany(EmployeeLicense::class);
    }
    public function conditions()
    {
        return $this->hasMany(EmployeeCondition::class);
    }
    public function inKindBenefits()
    {
        return $this->hasMany(EmployeeInKindBenefit::class);
    }
    public function commissions()
    {
        return $this->hasMany(EmployeeCommission::class);
    }
    public function bonuses()
    {
        return $this->hasMany(EmployeeBonus::class);
    }
    public function histories()
    {
        return $this->hasMany(EmployeeHistory::class);
    }
    public function aguinaldos()
    {
        return $this->hasMany(EmployeeAguinaldo::class);
    }
    public function assets()
    {
        return $this->hasMany(EmployeeAsset::class);
    }
    public function uniforms()
    {
        return $this->hasMany(EmployeeUniform::class);
    }
    public function trainings()
    {
        return $this->hasMany(EmployeeTraining::class);
    }
    public function warnings()
    {
        return $this->hasMany(EmployeeWarning::class);
    }
    public function scholarships()
    {
        return $this->hasMany(EmployeeScholarship::class);
    }
    public function leaves()
    {
        return $this->hasMany(EmployeeLeave::class);
    }
}
