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
        Schema::table('employees', function (Blueprint $table) {
            // Indices simples
            $table->index('email');
            $table->index('cedula');

            // Unico por empresa (evita duplicar correos dentro de la misma compañía)
            $table->unique(['company_id', 'email'], 'employees_company_email_unique');

            // Indices compuestos para consultas tipicas
            $table->index(['company_id', 'department_id'], 'employees_company_dept_idx');
            $table->index(['company_id', 'manager_id'], 'employees_company_manager_idx');

            // Filtros por modalidad
            $table->index(['is_payroll', 'is_outsourcing'], 'employees_payroll_outsourcing_idx');

            // Ordenaciones por fecha de contratación
            $table->index('hired_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->dropIndex(['cedula']);
            $table->dropUnique('employees_company_email_unique');
            $table->dropIndex('employees_company_dept_idx');
            $table->dropIndex('employees_company_manager_idx');
            $table->dropIndex('employees_payroll_outsourcing_idx');
            $table->dropIndex('hired_at');
        });
    }
};
