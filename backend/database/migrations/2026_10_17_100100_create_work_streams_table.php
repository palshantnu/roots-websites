<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_streams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_service_id')
                ->nullable()
                ->constrained('account_services')
                ->nullOnDelete();
            $table->string('title');
            $table->decimal('cost', 12, 2)->default(0);
            $table->decimal('advance_payment', 12, 2)->default(0);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_streams');
    }
};
