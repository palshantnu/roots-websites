<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_stream_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_stream_id')
                ->constrained('work_streams')
                ->cascadeOnDelete();
            $table->decimal('amount', 12, 2)->default(0);
            $table->date('due_date')->nullable();
            $table->string('note')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_stream_payments');
    }
};
