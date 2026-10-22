<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 20)->unique();
            $table->string('tagline')->nullable();
            $table->string('logo')->nullable();
            $table->text('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('gstin')->nullable();
            $table->string('footer_note')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $now = now();

        $footer = 'Professional • Confidential • Timely Support';

        DB::table('proposal_companies')->insert([
            ['name' => 'Roots Technology', 'code' => 'RT', 'tagline' => 'Research & Writing Services', 'footer_note' => $footer, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'RTS Publication', 'code' => 'RTSP', 'tagline' => null, 'footer_note' => $footer, 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'ThesisCraft Academy', 'code' => 'TCA', 'tagline' => null, 'footer_note' => $footer, 'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_companies');
    }
};
