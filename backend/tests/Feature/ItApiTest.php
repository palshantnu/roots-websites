<?php

use App\Enums\SiteCode;
use App\Models\CaseStudy;
use App\Models\Faq;
use App\Models\Post;
use App\Models\Project;
use App\Models\SectionItem;
use App\Models\Service;
use App\Models\SitePage;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Database\Seeders\ItSeeder;
use Database\Seeders\PublicationSeeder;
use Database\Seeders\ResearchSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns published it projects, optionally only featured ones', function () {
    Project::factory()->featured()->create(['name' => 'Featured', 'sort_order' => 1]);
    Project::factory()->create(['name' => 'Regular', 'sort_order' => 2]);
    Project::factory()->create(['name' => 'Hidden', 'is_published' => false]);
    Project::factory()->forSite(SiteCode::Research)->create(['name' => 'Research project']);

    $this->getJson('/api/it/projects')
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.name', 'Featured')
        ->assertJsonPath('data.0.featured', true)
        ->assertJsonStructure(['data' => [['id', 'slug', 'name', 'category', 'industry', 'technologies', 'description', 'image', 'year', 'featured']]]);

    $this->getJson('/api/it/projects?featured=1')->assertJsonCount(1, 'data')->assertJsonPath('data.0.name', 'Featured');
});

it('shows a single published it project', function () {
    $project = Project::factory()->create(['slug' => 'lumen-store']);
    $hidden = Project::factory()->create(['is_published' => false]);

    $this->getJson('/api/it/projects/lumen-store')->assertOk()->assertJsonPath('data.id', $project->id);
    $this->getJson("/api/it/projects/{$hidden->slug}")->assertNotFound();
});

it('returns published it case studies with results', function () {
    CaseStudy::factory()->create(['slug' => 'greenroute', 'results' => [['value' => '210%', 'label' => 'Organic growth']]]);
    CaseStudy::factory()->create(['is_published' => false]);

    $this->getJson('/api/it/case-studies')
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.results.0.value', '210%');

    $this->getJson('/api/it/case-studies/greenroute')->assertOk();
});

it('returns service categories, testimonial company and avatar, and post images', function () {
    Service::factory()->forSite(SiteCode::It)->create(['category' => 'Digital Marketing']);
    Testimonial::factory()->forSite(SiteCode::It)->create(['company' => 'Lumen', 'avatar' => 'https://example.com/a.jpg']);
    Post::factory()->forSite(SiteCode::It)->create(['slug' => 'a-post', 'image' => 'it/blog/cover.jpg']);

    $this->getJson('/api/it/services')->assertJsonPath('data.0.category', 'Digital Marketing');
    $this->getJson('/api/it/testimonials')
        ->assertJsonPath('data.0.company', 'Lumen')
        ->assertJsonPath('data.0.avatar', 'https://example.com/a.jpg');

    $image = $this->getJson('/api/it/posts/a-post')->json('data.image');
    expect($image)->toStartWith('http')->toEndWith('/storage/it/blog/cover.jpg');
});

it('returns page content blocks for service detail pages', function () {
    SitePage::factory()->forSite(SiteCode::It)->create([
        'slug' => 'seo',
        'cta_secondary_label' => 'Request a Quote',
        'content' => ['offerings' => [['title' => 'Technical SEO', 'icon' => 'search']], 'benefits' => ['Durable traffic']],
    ]);

    $this->getJson('/api/it/pages/seo')
        ->assertOk()
        ->assertJsonPath('data.content.offerings.0.title', 'Technical SEO')
        ->assertJsonPath('data.content.benefits.0', 'Durable traffic')
        ->assertJsonPath('data.cta.secondaryLabel', 'Request a Quote');
});

it('returns section item images', function () {
    SectionItem::factory()->forSite(SiteCode::It)->create(['section' => 'team', 'title' => 'Priya', 'image' => 'it/sections/priya.jpg']);

    expect($this->getJson('/api/it/sections')->json('data.team.0.image'))->toEndWith('/storage/it/sections/priya.jpg');
});

it('returns it settings', function () {
    SiteSetting::current(SiteCode::It)->update(['site_name' => 'Roots Technology', 'founded_year' => 2021, 'map_query' => 'Gwalior']);

    $this->getJson('/api/it/settings')
        ->assertJsonPath('data.siteName', 'Roots Technology')
        ->assertJsonPath('data.foundedYear', 2021)
        ->assertJsonPath('data.mapQuery', 'Gwalior');
});

it('seeds the it website without touching the other websites', function () {
    $this->seed(PublicationSeeder::class);
    $this->seed(ResearchSeeder::class);

    $before = [
        'publication' => $this->getJson('/api/publication/services')->json('data'),
        'research' => $this->getJson('/api/research/services')->json('data'),
    ];

    $this->seed(ItSeeder::class);

    $this->getJson('/api/it/services')->assertJsonCount(16, 'data');
    $this->getJson('/api/it/projects')->assertJsonCount(8, 'data');
    $this->getJson('/api/it/projects?featured=1')->assertJsonCount(6, 'data');
    $this->getJson('/api/it/case-studies')->assertJsonCount(4, 'data');
    $this->getJson('/api/it/testimonials')->assertJsonCount(6, 'data');
    $this->getJson('/api/it/faqs')->assertJsonCount(6, 'data');
    $this->getJson('/api/it/posts')->assertJsonCount(8, 'data');
    $this->getJson('/api/it/pages')->assertJsonCount(16, 'data');
    $this->getJson('/api/it/pages/website-development')->assertJsonCount(6, 'data.content.offerings');
    $this->getJson('/api/it/sections')->assertJsonCount(20, 'data.technologies')->assertJsonCount(8, 'data.team');
    $this->getJson('/api/it/settings')->assertJsonPath('data.siteName', 'Roots Technology');

    expect($this->getJson('/api/publication/services')->json('data'))->toBe($before['publication'])
        ->and($this->getJson('/api/research/services')->json('data'))->toBe($before['research']);

    // Re-running never duplicates anything.
    $this->seed(ItSeeder::class);
    $this->getJson('/api/it/services')->assertJsonCount(16, 'data');
    $this->getJson('/api/it/sections')->assertJsonCount(20, 'data.technologies');
    expect(Faq::forSite(SiteCode::It)->count())->toBe(6);
});
