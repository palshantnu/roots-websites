<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Makes the content tables that Publications already used (services, faqs,
 * posts, site_settings) site-aware so Research and IT can share them. Every
 * existing row is assigned to the Publications site, so Publications keeps
 * returning exactly the data it returned before.
 */
return new class extends Migration
{
    /**
     * @var list<string>
     */
    private array $tables = ['services', 'faqs', 'posts', 'site_settings'];

    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignId('site_id')->nullable()->after('id')->constrained()->restrictOnDelete();
            });
        }

        $publicationsSiteId = DB::table('sites')->where('code', 'publications')->value('id');

        foreach ($this->tables as $tableName) {
            DB::table($tableName)->whereNull('site_id')->update(['site_id' => $publicationsSiteId]);

            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignId('site_id')->nullable(false)->change();
            });
        }

        Schema::table('services', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->unique(['site_id', 'slug']);
            $table->string('icon')->nullable()->after('description');
            $table->string('tone')->nullable()->after('icon');
            $table->string('link')->nullable()->after('tone');
            $table->boolean('is_published')->default(true)->after('sort_order');
            $table->index(['site_id', 'is_published', 'sort_order']);
        });

        Schema::table('faqs', function (Blueprint $table) {
            $table->boolean('is_published')->default(true)->after('sort_order');
            $table->index(['site_id', 'is_published', 'sort_order']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->unique(['site_id', 'slug']);
            $table->string('author')->nullable()->after('category');
            $table->boolean('is_published')->default(true)->after('published_at');
            $table->index(['site_id', 'is_published', 'published_at']);
        });

        Schema::table('site_settings', function (Blueprint $table) {
            $table->unique('site_id');
            $table->string('twitter_url')->nullable()->after('linkedin_url');
            $table->text('footer_note')->nullable()->after('twitter_url');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropUnique(['site_id']);
            $table->dropColumn(['twitter_url', 'footer_note']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['site_id', 'is_published', 'published_at']);
            $table->dropUnique(['site_id', 'slug']);
            $table->dropColumn(['author', 'is_published']);
        });

        Schema::table('faqs', function (Blueprint $table) {
            $table->dropIndex(['site_id', 'is_published', 'sort_order']);
            $table->dropColumn('is_published');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropIndex(['site_id', 'is_published', 'sort_order']);
            $table->dropUnique(['site_id', 'slug']);
            $table->dropColumn(['icon', 'tone', 'link', 'is_published']);
        });

        $publicationsSiteId = DB::table('sites')->where('code', 'publications')->value('id');

        foreach ($this->tables as $tableName) {
            DB::table($tableName)->where('site_id', '!=', $publicationsSiteId)->delete();

            Schema::table($tableName, function (Blueprint $table) {
                $table->dropConstrainedForeignId('site_id');
            });
        }

        Schema::table('services', function (Blueprint $table) {
            $table->unique('slug');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->unique('slug');
        });
    }
};
