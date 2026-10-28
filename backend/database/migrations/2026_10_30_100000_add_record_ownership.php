<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Who created each record, so role users see only their own; and a role flag
 * for managers who may see everyone's records.
 */
return new class extends Migration
{
    private const TABLES = ['b2b_clients', 'b2b_projects', 'expenses', 'work_streams'];

    public function up(): void
    {
        foreach (self::TABLES as $name) {
            Schema::table($name, function (Blueprint $table) {
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            });
        }

        Schema::table('roles', function (Blueprint $table) {
            $table->boolean('sees_all_records')->default(false)->after('permissions');
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('sees_all_records');
        });

        foreach (self::TABLES as $name) {
            Schema::table($name, function (Blueprint $table) {
                $table->dropConstrainedForeignId('created_by');
            });
        }
    }
};
