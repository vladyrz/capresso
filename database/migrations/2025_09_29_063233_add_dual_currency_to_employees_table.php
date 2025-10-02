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
            // Soporte para doble moneda
            $table->decimal('gross_salary_amount_local', 15, 2)->nullable()->after('gross_salary_currency');
            $table->decimal('gross_salary_amount_usd', 15, 2)->nullable()->after('gross_salary_amount_local');
            $table->string('gross_salary_base_currency', 3)->nullable()->after('gross_salary_amount_usd');

            // Indices utiles para filtrar por montos
            $table->index('gross_salary_amount_local', 'employees_salary_local_idx');
            $table->index('gross_salary_amount_usd', 'employees_salary_usd_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropIndex('employees_salary_local_idx');
            $table->dropIndex('employees_salary_usd_idx');
            $table->dropColumn([
                'gross_salary_amount_local',
                'gross_salary_amount_usd',
                'gross_salary_base_currency',
            ]);
        });
    }
};
