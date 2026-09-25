<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Optional fields the IT website needs on the shared, site-scoped content
 * tables. Every column is nullable, so Publications and Research rows are
 * unaffected.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('category')->nullable()->after('title');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->string('image')->nullable()->after('color');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->string('company')->nullable()->after('role');
            $table->string('avatar')->nullable()->after('company');
        });

        Schema::table('section_items', function (Blueprint $table) {
            $table->string('image')->nullable()->after('meta');
        });

        Schema::table('site_pages', function (Blueprint $table) {
            $table->string('cta_secondary_label')->nullable()->after('cta_label');
            $table->json('content')->nullable()->after('cta_secondary_label');
        });

        Schema::table('site_settings', function (Blueprint $table) {
            $table->text('footer_text')->nullable()->after('footer_note');
            $table->unsignedSmallInteger('founded_year')->nullable()->after('footer_text');
            $table->string('map_query')->nullable()->after('founded_year');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['footer_text', 'founded_year', 'map_query']);
        });

        Schema::table('site_pages', function (Blueprint $table) {
            $table->dropColumn(['cta_secondary_label', 'content']);
        });

        Schema::table('section_items', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn(['company', 'avatar']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
