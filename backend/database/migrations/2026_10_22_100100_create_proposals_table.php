<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_company_id')
                ->constrained('proposal_companies')
                ->restrictOnDelete();
            $table->string('proposal_no')->unique();
            $table->date('proposal_date');

            // Client details are kept for the records (not printed on the PDF).
            $table->string('client_name');
            $table->string('client_organization')->nullable();
            $table->string('client_email')->nullable();
            $table->string('client_phone')->nullable();

            // Printed on the PDF, in this order.
            $table->string('subject');
            $table->string('greeting')->nullable();
            $table->longText('introduction')->nullable();
            $table->longText('package_includes')->nullable();
            $table->json('items')->nullable(); // payment stages: [{work, amount}]
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->longText('payment_terms')->nullable();
            $table->longText('deliverables')->nullable();

            // Filled in when the proposal is accepted and becomes a final project.
            $table->string('status', 20)->default('proposal')->index();
            $table->decimal('final_amount', 12, 2)->nullable();
            $table->decimal('advance_amount', 12, 2)->nullable();
            $table->timestamp('finalized_at')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
