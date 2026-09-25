<?php

use App\Enums\SampleType;
use App\Enums\SiteCode;
use App\Models\Sample;
use App\Models\SectionItem;
use App\Models\SitePage;
use App\Models\Testimonial;
use Database\Seeders\ResearchSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('local');
    Storage::fake('public');
});

it('returns published research pages with seo data', function () {
    SitePage::factory()->create(['slug' => 'samples', 'title' => 'Samples', 'meta_title' => 'Samples | Research']);
    SitePage::factory()->create(['slug' => 'draft', 'is_published' => false]);
    SitePage::factory()->forSite(SiteCode::Publications)->create(['slug' => 'publication-page']);

    $this->getJson('/api/research/pages')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.slug', 'samples')
        ->assertJsonPath('data.0.seo.title', 'Samples | Research');

    $this->getJson('/api/research/pages/samples')->assertOk()->assertJsonPath('data.title', 'Samples');
    $this->getJson('/api/research/pages/draft')->assertNotFound();
    $this->getJson('/api/research/pages/publication-page')->assertNotFound();
});

it('returns published section items grouped by section', function () {
    SectionItem::factory()->create(['section' => 'hero_stats', 'title' => 'Second', 'sort_order' => 2]);
    SectionItem::factory()->create(['section' => 'hero_stats', 'title' => 'First', 'sort_order' => 1]);
    SectionItem::factory()->create(['section' => 'process_steps', 'title' => 'Step']);
    SectionItem::factory()->create(['section' => 'process_steps', 'title' => 'Hidden', 'is_published' => false]);
    SectionItem::factory()->forSite(SiteCode::It)->create(['section' => 'process_steps', 'title' => 'IT step']);

    $this->getJson('/api/research/sections')
        ->assertOk()
        ->assertJsonPath('data.hero_stats.0.title', 'First')
        ->assertJsonPath('data.hero_stats.1.title', 'Second')
        ->assertJsonCount(1, 'data.process_steps');

    $this->getJson('/api/research/sections?section=process_steps')
        ->assertJsonMissingPath('data.hero_stats')
        ->assertJsonPath('data.process_steps.0.title', 'Step');
});

it('returns published testimonials of the research site only', function () {
    Testimonial::factory()->create(['name' => 'Research scholar']);
    Testimonial::factory()->create(['name' => 'Hidden', 'is_published' => false]);
    Testimonial::factory()->forSite(SiteCode::Publications)->create(['name' => 'Publications author']);

    $this->getJson('/api/research/testimonials')
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Research scholar');
});

it('lists published samples of every category', function () {
    Sample::factory()->ofType(SampleType::ResearchPaper)->withDocument()->create(['title' => 'Paper']);
    Sample::factory()->ofType(SampleType::Thesis)->create(['title' => 'Thesis']);
    Sample::factory()->ofType(SampleType::Synopsis)->create(['title' => 'Synopsis']);
    Sample::factory()->ofType(SampleType::Thesis)->unpublished()->create(['title' => 'Draft']);
    Sample::factory()->forSite(SiteCode::Publications)->create(['title' => 'Publications sample']);
    Sample::factory()->forSite(SiteCode::It)->create(['title' => 'IT sample']);

    $response = $this->getJson('/api/research/samples')->assertOk()->assertJsonCount(3, 'data');

    expect(collect($response->json('data'))->pluck('title')->all())
        ->toEqualCanonicalizing(['Paper', 'Thesis', 'Synopsis']);

    $paper = collect($response->json('data'))->firstWhere('title', 'Paper');
    expect($paper['type'])->toBe('research_paper')
        ->and($paper['typeLabel'])->toBe('Research Paper')
        ->and($paper['file']['extension'])->toBe('pdf')
        ->and($paper['file']['downloadUrl'])->toEndWith('/api/research/samples/'.$paper['slug'].'/download');
});

it('filters samples by type', function (string $type, string $title) {
    Sample::factory()->ofType(SampleType::ResearchPaper)->create(['title' => 'Paper']);
    Sample::factory()->ofType(SampleType::Thesis)->create(['title' => 'Thesis']);
    Sample::factory()->ofType(SampleType::Synopsis)->create(['title' => 'Synopsis']);

    $this->getJson("/api/research/samples?type={$type}")
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.title', $title);
})->with([
    ['research_paper', 'Paper'],
    ['thesis', 'Thesis'],
    ['synopsis', 'Synopsis'],
]);

it('rejects an unknown sample type', function () {
    $this->getJson('/api/research/samples?type=novel')
        ->assertUnprocessable()
        ->assertJsonValidationErrors('type');
});

it('orders samples by display order', function () {
    Sample::factory()->create(['title' => 'Later', 'sort_order' => 5]);
    Sample::factory()->create(['title' => 'Sooner', 'sort_order' => 1]);

    $this->getJson('/api/research/samples')->assertJsonPath('data.0.title', 'Sooner');
});

it('shows a single published sample', function () {
    $sample = Sample::factory()->create();
    $draft = Sample::factory()->unpublished()->create();
    $other = Sample::factory()->forSite(SiteCode::Publications)->create();

    $this->getJson("/api/research/samples/{$sample->slug}")->assertOk()->assertJsonPath('data.id', $sample->id);
    $this->getJson("/api/research/samples/{$draft->slug}")->assertNotFound();
    $this->getJson("/api/research/samples/{$other->slug}")->assertNotFound();
});

it('downloads a published sample document with a safe file name', function () {
    $sample = Sample::factory()->withDocument('%PDF-1.4 test')->create(['slug' => 'my-thesis']);

    $response = $this->get('/api/research/samples/my-thesis/download')->assertOk();

    expect($response->headers->get('content-disposition'))->toContain('attachment')->toContain('my-thesis.pdf')
        ->and($response->headers->get('content-type'))->toBe('application/pdf')
        ->and($response->headers->get('x-content-type-options'))->toBe('nosniff')
        ->and($response->streamedContent())->toBe('%PDF-1.4 test');
});

it('serves a published sample document inline for viewing', function () {
    Sample::factory()->withDocument()->create(['slug' => 'viewable']);

    $response = $this->get('/api/research/samples/viewable/file')->assertOk();

    expect($response->headers->get('content-disposition'))->toContain('inline');
});

it('does not serve documents of unpublished, missing or foreign samples', function () {
    $draft = Sample::factory()->unpublished()->withDocument()->create();
    $foreign = Sample::factory()->forSite(SiteCode::Publications)->withDocument()->create();
    $withoutFile = Sample::factory()->create();

    $this->get("/api/research/samples/{$draft->slug}/download")->assertNotFound();
    $this->get("/api/research/samples/{$draft->slug}/file")->assertNotFound();
    $this->get("/api/research/samples/{$foreign->slug}/download")->assertNotFound();
    $this->get("/api/research/samples/{$withoutFile->slug}/download")->assertNotFound();
});

it('stores sample documents on the private disk', function () {
    $sample = Sample::factory()->withDocument()->create();

    Storage::disk('local')->assertExists($sample->file_path);
    Storage::disk('public')->assertMissing($sample->file_path);
    expect($sample->fresh()->file_size)->toBeGreaterThan(0)
        ->and($sample->fresh()->file_mime)->toBe('application/pdf');
});

it('seeds the research website without touching other sites', function () {
    $this->seed(ResearchSeeder::class);

    $this->getJson('/api/research/services')->assertJsonCount(4, 'data');
    $this->getJson('/api/research/faqs')->assertJsonCount(10, 'data');
    $this->getJson('/api/research/testimonials')->assertJsonCount(5, 'data');
    $this->getJson('/api/research/posts')->assertJsonCount(6, 'data');
    $this->getJson('/api/research/pages/home')->assertJsonPath('data.highlight', 'PhD & Masters');
    $this->getJson('/api/research/settings')->assertJsonPath('data.siteName', 'ThesisCraft Academy');
    $this->getJson('/api/research/sections')->assertJsonCount(4, 'data.hero_stats');

    $this->getJson('/api/publication/services')->assertJsonCount(0, 'data');
    $this->getJson('/api/publication/faqs')->assertJsonCount(0, 'data');

    // Seeding twice must not duplicate anything.
    $this->seed(ResearchSeeder::class);
    $this->getJson('/api/research/services')->assertJsonCount(4, 'data');
    $this->getJson('/api/research/sections')->assertJsonCount(4, 'data.hero_stats');
});
