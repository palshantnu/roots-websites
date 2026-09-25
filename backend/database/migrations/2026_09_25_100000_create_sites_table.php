<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('domain')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $now = now();

        DB::table('sites')->insert([
            ['name' => 'RTS Publication', 'code' => 'publications', 'domain' => null, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'ThesisCraft Academy', 'code' => 'research', 'domain' => null, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Roots Technology', 'code' => 'it', 'domain' => null, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
