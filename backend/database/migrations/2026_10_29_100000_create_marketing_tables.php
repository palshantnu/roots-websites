<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_channels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('type', 20)->default('platform');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('marketing_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_channel_id')->constrained()->restrictOnDelete();
            $table->foreignId('account_service_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('proposal_company_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('campaign')->nullable()->index();
            $table->string('paid_to')->nullable()->index();
            $table->decimal('amount', 12, 2);
            $table->date('spent_on')->index();
            $table->date('runs_from')->nullable();
            $table->date('runs_to')->nullable();
            $table->string('mode', 30)->nullable();
            $table->string('reference')->nullable();
            $table->unsignedInteger('leads')->nullable();
            $table->string('receipt')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        $now = now();
        $defaults = [
            ['Google Ads', 'platform'],
            ['Meta Ads (Facebook / Instagram)', 'platform'],
            ['LinkedIn Ads', 'platform'],
            ['YouTube', 'platform'],
            ['SEO / Website', 'platform'],
            ['Freelancer / Influencer', 'person'],
            ['Marketing agency', 'agency'],
            ['Print & offline', 'offline'],
            ['Other', 'other'],
        ];

        DB::table('marketing_channels')->insert(
            collect($defaults)->map(fn (array $row, int $i) => [
                'name' => $row[0],
                'type' => $row[1],
                'is_active' => true,
                'sort_order' => $i,
                'created_at' => $now,
                'updated_at' => $now,
            ])->all()
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_expenses');
        Schema::dropIfExists('marketing_channels');
    }
};
