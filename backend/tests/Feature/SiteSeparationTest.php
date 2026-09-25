<?php

use App\Enums\SiteCode;
use App\Models\Faq;
use App\Models\Post;
use App\Models\Sample;
use App\Models\SectionItem;
use App\Models\Service;
use App\Models\Site;
use App\Models\SitePage;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates the three websites by code', function () {
    expect(Site::pluck('code')->all())->toEqualCanonicalizing(['publications', 'research', 'it']);
});

it('keeps services separated per website', function () {
    Service::factory()->create(['title' => 'Cover Design', 'slug' => 'cover-design']);
    Service::factory()->forSite(SiteCode::Research)->create(['title' => 'Thesis Support', 'slug' => 'thesis-support']);
    Service::factory()->forSite(SiteCode::It)->create(['title' => 'Web Development', 'slug' => 'web-development']);

    $this->getJson('/api/publication/services')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.title', 'Cover Design');

    $this->getJson('/api/research/services')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.title', 'Thesis Support');

    $this->getJson('/api/publication/services/thesis-support')->assertNotFound();
    $this->getJson('/api/research/services/cover-design')->assertNotFound();
    $this->getJson('/api/research/services/web-development')->assertNotFound();
});

it('allows the same slug on different websites', function () {
    Service::factory()->create(['title' => 'Editing (Publications)', 'slug' => 'editing']);
    Service::factory()->forSite(SiteCode::Research)->create(['title' => 'Editing (Research)', 'slug' => 'editing']);

    $this->getJson('/api/publication/services/editing')->assertJsonPath('data.title', 'Editing (Publications)');
    $this->getJson('/api/research/services/editing')->assertJsonPath('data.title', 'Editing (Research)');
});

it('keeps faqs and posts separated per website', function () {
    Faq::factory()->create(['question' => 'Publications question?']);
    Faq::factory()->forSite(SiteCode::Research)->create(['question' => 'Research question?']);
    Post::factory()->create(['slug' => 'publication-post']);
    Post::factory()->forSite(SiteCode::Research)->create(['slug' => 'research-post']);

    $this->getJson('/api/publication/faqs')->assertJsonCount(1, 'data')->assertJsonPath('data.0.question', 'Publications question?');
    $this->getJson('/api/research/faqs')->assertJsonCount(1, 'data')->assertJsonPath('data.0.question', 'Research question?');

    $this->getJson('/api/publication/posts')->assertJsonCount(1, 'data')->assertJsonPath('data.0.slug', 'publication-post');
    $this->getJson('/api/research/posts')->assertJsonCount(1, 'data')->assertJsonPath('data.0.slug', 'research-post');
    $this->getJson('/api/research/posts/publication-post')->assertNotFound();
});

it('keeps settings separated per website', function () {
    SiteSetting::current(SiteCode::Publications)->update(['site_name' => 'RTS Publication']);
    SiteSetting::current(SiteCode::Research)->update(['site_name' => 'ThesisCraft Academy']);

    $this->getJson('/api/publication/settings')->assertJsonPath('data.siteName', 'RTS Publication');
    $this->getJson('/api/research/settings')->assertJsonPath('data.siteName', 'ThesisCraft Academy');
});

it('never returns research-only content from publication routes', function () {
    SitePage::factory()->create(['slug' => 'home']);
    SectionItem::factory()->create();
    Testimonial::factory()->create();
    Sample::factory()->create(['slug' => 'a-sample']);

    $this->getJson('/api/publication/pages/home')->assertNotFound();
    $this->getJson('/api/publication/sections')->assertNotFound();
    $this->getJson('/api/publication/testimonials')->assertNotFound();
    $this->getJson('/api/publication/samples')->assertNotFound();
});

it('ignores attempts to pick another site through the request', function () {
    Service::factory()->create(['title' => 'Publications only']);

    $this->getJson('/api/research/services?site=publications&site_id=1')
        ->assertOk()
        ->assertJsonCount(0, 'data');
});

it('hides unpublished shared content', function () {
    Service::factory()->forSite(SiteCode::Research)->create(['slug' => 'visible']);
    Service::factory()->forSite(SiteCode::Research)->create(['slug' => 'hidden', 'is_published' => false]);

    $this->getJson('/api/research/services')->assertJsonCount(1, 'data')->assertJsonPath('data.0.slug', 'visible');
    $this->getJson('/api/research/services/hidden')->assertNotFound();
});

it('disables every route of an inactive website', function () {
    Site::where('code', 'research')->update(['is_active' => false]);

    $this->getJson('/api/research/services')->assertNotFound();
    $this->getJson('/api/publication/services')->assertOk();
});

it('keeps the existing publication api response shape', function () {
    Service::factory()->create(['title' => 'Cover Design', 'slug' => 'cover-design', 'description' => 'Covers.']);
    Post::factory()->create(['slug' => 'a-post', 'published_at' => '2024-08-12']);

    $this->getJson('/api/publication/services')
        ->assertJsonStructure(['data' => [['id', 'slug', 'title', 'description']]]);

    $this->getJson('/api/publication/posts/a-post')
        ->assertJsonPath('data.date', '12 Aug 2024')
        ->assertJsonStructure(['data' => ['id', 'slug', 'title', 'category', 'color', 'date', 'time', 'excerpt', 'body']]);

    $this->getJson('/api/publication/settings')
        ->assertJsonStructure(['data' => ['siteName', 'tagline', 'hero' => ['eyebrow', 'title', 'copy'], 'stats', 'testimonial', 'contact', 'socials']]);
});
