<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Makes contact messages site-aware so Research and IT can collect enquiries
 * too. Existing messages came from the Publications contact form, so they are
 * assigned to the Publications site. `message` becomes optional because the
 * Research form collects a subject and city instead of free text.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->foreignId('site_id')->nullable()->after('id')->constrained()->restrictOnDelete();
            $table->string('company')->nullable()->after('phone');
            $table->string('city')->nullable()->after('company');
            $table->string('service')->nullable()->after('subject');
            $table->string('budget')->nullable()->after('service');
            $table->text('message')->nullable()->change();
        });

        $publicationsSiteId = DB::table('sites')->where('code', 'publications')->value('id');

        DB::table('contact_messages')->whereNull('site_id')->update(['site_id' => $publicationsSiteId]);

        Schema::table('contact_messages', function (Blueprint $table) {
            $table->foreignId('site_id')->nullable(false)->change();
            $table->index(['site_id', 'read', 'created_at']);
        });
    }

    public function down(): void
    {
        $publicationsSiteId = DB::table('sites')->where('code', 'publications')->value('id');

        DB::table('contact_messages')->where('site_id', '!=', $publicationsSiteId)->delete();
        DB::table('contact_messages')->whereNull('message')->update(['message' => '']);

        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropIndex(['site_id', 'read', 'created_at']);
            $table->dropConstrainedForeignId('site_id');
            $table->dropColumn(['company', 'city', 'service', 'budget']);
            $table->text('message')->nullable(false)->change();
        });
    }
};
