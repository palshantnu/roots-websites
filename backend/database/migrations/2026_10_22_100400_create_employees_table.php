<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('designation')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('email')->nullable();
            $table->date('joining_date')->nullable();
            $table->decimal('monthly_salary', 12, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::table('work_streams', function (Blueprint $table) {
            $table->foreignId('employee_id')->nullable()->after('account_service_id')
                ->constrained('employees')->nullOnDelete();
            $table->string('status', 20)->default('in_progress')->after('title')->index();
        });
    }

    public function down(): void
    {
        Schema::table('work_streams', function (Blueprint $table) {
            $table->dropConstrainedForeignId('employee_id');
            $table->dropColumn('status');
        });

        Schema::dropIfExists('employees');
    }
};
