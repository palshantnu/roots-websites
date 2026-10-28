<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('b2b_clients', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('contact_person')->nullable();
            $table->string('designation')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('alt_phone', 50)->nullable();
            $table->string('website')->nullable();
            $table->string('gstin', 20)->nullable();
            $table->string('pan', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('pincode', 20)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('b2b_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('b2b_client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('proposal_company_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('account_service_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained()->nullOnDelete();
            $table->string('project_no')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('completed_on')->nullable();
            $table->decimal('value', 12, 2)->default(0);
            $table->string('status', 20)->default('in_progress')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('b2b_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('b2b_project_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->date('paid_on');
            $table->string('mode', 30)->nullable();
            $table->string('reference')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('b2b_payments');
        Schema::dropIfExists('b2b_projects');
        Schema::dropIfExists('b2b_clients');
    }
};
