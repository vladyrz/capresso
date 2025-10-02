<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            // Empresa & Organización
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('manager_id')->nullable()->constrained('employees')->nullOnDelete(); // Jefe inmediato

            // Información personal
            $table->string('name'); // Nombre completo
            $table->string('cedula')->nullable(); // Cedula
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->date('hired_at')->nullable(); // Fecha de contratación
            $table->date('birth_date')->nullable(); // Fecha de nacimiento
            $table->string('blood_type', 10)->nullable(); // Tipo de sangre

            // Documentación de viaje
            $table->string('passport_number')->nullable(); // Pasaporte al día (número)
            $table->string('passport_expires_at')->nullable(); // Vencimiento del pasaporte
            $table->string('us_visa_expires_at')->nullable(); // Vencimiento de la visa

            // Dirección
            $table->string('country')->nullable(); // País
            $table->string('address')->nullable(); // Dirección de residencia

            // Academico
            $table->string('academic_degree')->nullable(); // Grado académico (texto libre)

            // Relacion laboral
            $table->boolean('is_payroll')->default(true); // Planilla
            $table->boolean('is_outsourcing')->default(false); // Outsourcing
            $table->string('position')->nullable(); // Puesto (si se maneja)
            $table->text('main_function')->nullable(); // Función principal

            // Compensación
            $table->decimal('gross_salary_amount', 15, 2)->nullable(); // Salario bruto
            $table->string('gross_salary_currency', 3)->nullable(); // Moneda (USD, Local, etc.)
            $table->decimal('social_chargers', 15, 2)->nullable(); // Cargas sociales

            // Licencias
            $table->boolean('has_car_license')->default(false); // Licencia de coche (si/no)
            $table->boolean('has_motorcycle_license')->default(false); // Licencia de moto (si/no)

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
