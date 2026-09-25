<?php

use App\Enums\SiteCode;
use App\Filament\Clusters\It\Pages\ManageItSettings;
use App\Filament\Clusters\It\Resources\CaseStudyResource\Pages\CreateCaseStudy;
use App\Filament\Clusters\It\Resources\PostResource\Pages\CreatePost;
use App\Filament\Clusters\It\Resources\ProjectResource\Pages\CreateProject;
use App\Filament\Clusters\It\Resources\ProjectResource\Pages\EditProject;
use App\Filament\Clusters\It\Resources\ProjectResource\Pages\ListProjects;
use App\Filament\Clusters\It\Resources\SectionItemResource\Pages\EditSectionItem;
use App\Filament\Clusters\It\Resources\ServiceResource\Pages\CreateService;
use App\Filament\Clusters\It\Resources\SitePageResource\Pages\EditSitePage;
use App\Filament\Clusters\It\Resources\TestimonialResource\Pages\EditTestimonial;
use App\Models\CaseStudy;
use App\Models\Faq;
use App\Models\Post;
use App\Models\Project;
use App\Models\SectionItem;
use App\Models\Service;
use App\Models\Site;
use App\Models\SitePage;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');

    $this->admin = User::factory()->admin()->create();
    SectionItem::factory()->forSite(SiteCode::It)->create(['section' => 'project_categories', 'title' => 'Website']);
    SectionItem::factory()->forSite(SiteCode::It)->create(['section' => 'blog_categories', 'title' => 'SEO']);
});

function itImage(string $name = 'cover.jpg'): UploadedFile
{
    return UploadedFile::fake()->image($name, 800, 600);
}

it('loads every it admin page', function () {
    $page = SitePage::factory()->forSite(SiteCode::It)->create(['slug' => 'website-development', 'content' => ['offerings' => [['title' => 'Sites', 'icon' => 'globe', 'description' => 'x']]]]);
    $service = Service::factory()->forSite(SiteCode::It)->create(['category' => 'IT Services']);
    $project = Project::factory()->create();
    $caseStudy = CaseStudy::factory()->create();
    $item = SectionItem::factory()->forSite(SiteCode::It)->create();
    $testimonial = Testimonial::factory()->forSite(SiteCode::It)->create();
    $faq = Faq::factory()->forSite(SiteCode::It)->create();
    $post = Post::factory()->forSite(SiteCode::It)->create();

    $urls = [
        '/admin/it/pages', '/admin/it/pages/create', "/admin/it/pages/{$page->id}/edit",
        '/admin/it/services', '/admin/it/services/create', "/admin/it/services/{$service->id}/edit",
        '/admin/it/projects', '/admin/it/projects/create', "/admin/it/projects/{$project->id}/edit",
        '/admin/it/case-studies', '/admin/it/case-studies/create', "/admin/it/case-studies/{$caseStudy->id}/edit",
        '/admin/it/sections', '/admin/it/sections/create', "/admin/it/sections/{$item->id}/edit",
        '/admin/it/testimonials', '/admin/it/testimonials/create', "/admin/it/testimonials/{$testimonial->id}/edit",
        '/admin/it/faqs', '/admin/it/faqs/create', "/admin/it/faqs/{$faq->id}/edit",
        '/admin/it/articles', '/admin/it/articles/create', "/admin/it/articles/{$post->id}/edit",
        '/admin/it/settings',
    ];

    foreach ($urls as $url) {
        $this->actingAs($this->admin)->get($url)->assertOk();
    }
});

it('denies the it admin to non-administrators and guests', function () {
    $this->actingAs(User::factory()->create())->get('/admin/it/projects')->assertForbidden();
    auth()->logout();
    $this->get('/admin/it/projects')->assertRedirect('/admin/login');
});

it('cannot open other websites records from the it admin, or it records elsewhere', function () {
    $researchService = Service::factory()->forSite(SiteCode::Research)->create();
    $publicationFaq = Faq::factory()->create();
    $researchPage = SitePage::factory()->create();
    $itService = Service::factory()->forSite(SiteCode::It)->create();

    $this->actingAs($this->admin)->get("/admin/it/services/{$researchService->id}/edit")->assertNotFound();
    $this->actingAs($this->admin)->get("/admin/it/faqs/{$publicationFaq->id}/edit")->assertNotFound();
    $this->actingAs($this->admin)->get("/admin/it/pages/{$researchPage->id}/edit")->assertNotFound();
    $this->actingAs($this->admin)->get("/admin/research/services/{$itService->id}/edit")->assertNotFound();
    $this->actingAs($this->admin)->get("/admin/services/{$itService->id}/edit")->assertNotFound();
});

it('lists only it projects', function () {
    $itProject = Project::factory()->create();
    $researchProject = Project::factory()->forSite(SiteCode::Research)->create();

    $this->actingAs($this->admin);

    Livewire::test(ListProjects::class)
        ->assertCanSeeTableRecords([$itProject])
        ->assertCanNotSeeTableRecords([$researchProject]);
});

it('creates a project with an uploaded image for the it website', function () {
    $this->actingAs($this->admin);

    Livewire::test(CreateProject::class)
        ->fillForm([
            'name' => 'Lumen Store',
            'slug' => 'lumen-store',
            'category' => 'Website',
            'industry' => 'Retail',
            'year' => 2025,
            'technologies' => ['Next.js', 'Shopify'],
            'is_featured' => true,
        ])
        ->set('data.image', [itImage()])
        ->call('create')
        ->assertHasNoFormErrors();

    $project = Project::sole();

    expect($project->site_id)->toBe(Site::findByCode(SiteCode::It)->id)
        ->and($project->technologies)->toBe(['Next.js', 'Shopify'])
        ->and($project->is_featured)->toBeTrue()
        ->and($project->image)->toStartWith('it/projects/');

    Storage::disk('public')->assertExists($project->image);
    expect($this->getJson('/api/it/projects/lumen-store')->json('data.image'))->toEndWith('/storage/'.$project->image);
});

it('rejects non-image uploads', function () {
    $this->actingAs($this->admin);

    Livewire::test(CreateProject::class)
        ->fillForm(['name' => 'Bad', 'slug' => 'bad', 'category' => 'Website'])
        ->set('data.image', [UploadedFile::fake()->createWithContent('notes.txt', 'text')])
        ->call('create')
        ->assertHasFormErrors(['image']);
});

it('replaces a project image and deletes the old file', function () {
    Storage::disk('public')->put('it/projects/old.jpg', 'old');
    $project = Project::factory()->create(['image' => 'it/projects/old.jpg']);

    $this->actingAs($this->admin);

    Livewire::test(EditProject::class, ['record' => $project->getRouteKey()])
        ->set('data.image', [itImage('new.jpg')])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($project->refresh()->image)->not->toBe('it/projects/old.jpg');
    Storage::disk('public')->assertMissing('it/projects/old.jpg');
    Storage::disk('public')->assertExists($project->image);
});

it('deletes a project with its image and never touches external image urls', function () {
    Storage::disk('public')->put('it/projects/gone.jpg', 'x');
    $uploaded = Project::factory()->create(['image' => 'it/projects/gone.jpg']);
    $external = Project::factory()->create(['image' => 'https://picsum.photos/seed/x/900/600']);

    $this->actingAs($this->admin);

    Livewire::test(ListProjects::class)->callTableAction('delete', $uploaded);
    Livewire::test(ListProjects::class)->callTableAction('delete', $external);

    expect(Project::count())->toBe(0);
    Storage::disk('public')->assertMissing('it/projects/gone.jpg');
});

it('toggles publish and reorders projects', function () {
    $first = Project::factory()->create(['sort_order' => 1]);
    $second = Project::factory()->create(['sort_order' => 2]);

    $this->actingAs($this->admin);

    Livewire::test(ListProjects::class)->call('updateTableColumnState', 'is_published', (string) $first->id, false);
    expect($first->refresh()->is_published)->toBeFalse();

    Livewire::test(ListProjects::class)->call('reorderTable', [(string) $second->id, (string) $first->id]);
    expect($second->refresh()->sort_order)->toBeLessThan($first->refresh()->sort_order);
});

it('creates a case study with results', function () {
    $this->actingAs($this->admin);

    Livewire::test(CreateCaseStudy::class)
        ->fillForm([
            'title' => 'Headless replatform',
            'slug' => 'headless-replatform',
            'client' => 'Lumen',
            'results' => [['value' => '-55%', 'label' => 'Page load time']],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(CaseStudy::sole()->results)->toBe([['value' => '-55%', 'label' => 'Page load time']])
        ->and(CaseStudy::sole()->site->code)->toBe('it');
});

it('creates it services and articles for the it website only', function () {
    $this->actingAs($this->admin);

    Livewire::test(CreateService::class)
        ->fillForm(['title' => 'Website Development', 'slug' => 'website-development', 'category' => 'IT Services', 'icon' => 'monitor'])
        ->call('create')
        ->assertHasNoFormErrors();

    Livewire::test(CreatePost::class)
        ->fillForm(['title' => 'Core Web Vitals', 'slug' => 'core-web-vitals', 'category' => 'SEO', 'published_at' => '2026-08-18', 'body' => "Intro.\n\n## Heading"])
        ->set('data.image', [itImage()])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->getJson('/api/it/services')->assertJsonCount(1, 'data');
    $this->getJson('/api/it/posts')->assertJsonCount(1, 'data');
    $this->getJson('/api/research/services')->assertJsonCount(0, 'data');
    $this->getJson('/api/publication/posts')->assertJsonCount(0, 'data');
});

it('edits service page content blocks and seo', function () {
    $page = SitePage::factory()->forSite(SiteCode::It)->create(['slug' => 'seo', 'name' => 'SEO', 'content' => ['benefits' => ['Old']]]);

    $this->actingAs($this->admin);

    Livewire::test(EditSitePage::class, ['record' => $page->getRouteKey()])
        ->fillForm([
            'meta_title' => 'SEO Services',
            'content.benefits' => ['Durable traffic', 'Honest reporting'],
            'content.aside_title' => 'Every engagement includes',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $page->refresh();
    expect($page->meta_title)->toBe('SEO Services')
        ->and($page->content['benefits'])->toBe(['Durable traffic', 'Honest reporting'])
        ->and($page->content['aside_title'])->toBe('Every engagement includes');
});

it('uploads team photos and testimonial avatars', function () {
    $member = SectionItem::factory()->forSite(SiteCode::It)->create(['section' => 'team', 'title' => 'Priya']);
    $testimonial = Testimonial::factory()->forSite(SiteCode::It)->create();

    $this->actingAs($this->admin);

    Livewire::test(EditSectionItem::class, ['record' => $member->getRouteKey()])
        ->set('data.image', [itImage('priya.jpg')])
        ->call('save')
        ->assertHasNoFormErrors();

    Livewire::test(EditTestimonial::class, ['record' => $testimonial->getRouteKey()])
        ->set('data.avatar', [itImage('face.jpg')])
        ->call('save')
        ->assertHasNoFormErrors();

    Storage::disk('public')->assertExists($member->refresh()->image);
    Storage::disk('public')->assertExists($testimonial->refresh()->avatar);
});

it('saves it settings without touching other websites', function () {
    SiteSetting::current(SiteCode::Research)->update(['site_name' => 'ThesisCraft Academy']);

    $this->actingAs($this->admin);

    Livewire::test(ManageItSettings::class)
        ->fillForm(['site_name' => 'Roots Technology', 'tagline' => 'Building Digital Solutions', 'founded_year' => 2021])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(SiteSetting::current(SiteCode::It)->founded_year)->toBe(2021)
        ->and(SiteSetting::current(SiteCode::Research)->site_name)->toBe('ThesisCraft Academy');
});
