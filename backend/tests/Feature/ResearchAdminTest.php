<?php

use App\Enums\SampleType;
use App\Enums\SiteCode;
use App\Filament\Clusters\Research\Pages\ManageResearchSettings;
use App\Filament\Clusters\Research\Resources\SampleResource\Pages\CreateSample;
use App\Filament\Clusters\Research\Resources\SampleResource\Pages\EditSample;
use App\Filament\Clusters\Research\Resources\SampleResource\Pages\ListSamples;
use App\Filament\Clusters\Research\Resources\ServiceResource\Pages\CreateService;
use App\Models\Faq;
use App\Models\Post;
use App\Models\Sample;
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
    Storage::fake('local');
    Storage::fake('public');

    $this->admin = User::factory()->admin()->create();
});

function fakePdf(string $name = 'sample.pdf'): UploadedFile
{
    return UploadedFile::fake()->createWithContent($name, "%PDF-1.4\n1 0 obj\n<<>>\nendobj\ntrailer\n<<>>\n%%EOF\n");
}

it('denies the admin panel to users who are not administrators', function () {
    $this->actingAs(User::factory()->create())->get('/admin')->assertForbidden();
    $this->actingAs(User::factory()->create())->get('/admin/research/samples')->assertForbidden();
});

it('redirects guests to the login page', function () {
    $this->get('/admin/research/samples')->assertRedirect('/admin/login');
});

it('loads every research admin page', function () {
    $page = SitePage::factory()->create(['slug' => 'home']);
    $service = Service::factory()->forSite(SiteCode::Research)->create();
    $item = SectionItem::factory()->create();
    $testimonial = Testimonial::factory()->create();
    $faq = Faq::factory()->forSite(SiteCode::Research)->create();
    $post = Post::factory()->forSite(SiteCode::Research)->create();
    $sample = Sample::factory()->create();

    $pages = [
        '/admin/research/pages', '/admin/research/pages/create', "/admin/research/pages/{$page->id}/edit",
        '/admin/research/services', '/admin/research/services/create', "/admin/research/services/{$service->id}/edit",
        '/admin/research/sections', '/admin/research/sections/create', "/admin/research/sections/{$item->id}/edit",
        '/admin/research/testimonials', '/admin/research/testimonials/create', "/admin/research/testimonials/{$testimonial->id}/edit",
        '/admin/research/faqs', '/admin/research/faqs/create', "/admin/research/faqs/{$faq->id}/edit",
        '/admin/research/articles', '/admin/research/articles/create', "/admin/research/articles/{$post->id}/edit",
        '/admin/research/samples', '/admin/research/samples/create', "/admin/research/samples/{$sample->id}/edit",
        '/admin/research/settings',
    ];

    foreach ($pages as $url) {
        $this->actingAs($this->admin)->get($url)->assertOk();
    }
});

it('cannot open records of another website from the research admin', function () {
    $publicationService = Service::factory()->create();
    $publicationFaq = Faq::factory()->create();
    $itSample = Sample::factory()->forSite(SiteCode::It)->create();

    $this->actingAs($this->admin)->get("/admin/research/services/{$publicationService->id}/edit")->assertNotFound();
    $this->actingAs($this->admin)->get("/admin/research/faqs/{$publicationFaq->id}/edit")->assertNotFound();
    $this->actingAs($this->admin)->get("/admin/research/samples/{$itSample->id}/edit")->assertNotFound();
});

it('cannot open research records from the publications admin', function () {
    $researchService = Service::factory()->forSite(SiteCode::Research)->create();

    $this->actingAs($this->admin)->get("/admin/services/{$researchService->id}/edit")->assertNotFound();
});

it('lists only research samples, with one tab per category', function () {
    $paper = Sample::factory()->ofType(SampleType::ResearchPaper)->create();
    $thesis = Sample::factory()->ofType(SampleType::Thesis)->create();
    $foreign = Sample::factory()->forSite(SiteCode::Publications)->create();

    $this->actingAs($this->admin);

    Livewire::test(ListSamples::class)
        ->assertCanSeeTableRecords([$paper, $thesis])
        ->assertCanNotSeeTableRecords([$foreign]);

    Livewire::test(ListSamples::class, ['activeTab' => 'thesis'])
        ->assertCanSeeTableRecords([$thesis])
        ->assertCanNotSeeTableRecords([$paper]);
});

it('creates a sample for the research website with an uploaded document', function (SampleType $type) {
    $this->actingAs($this->admin);

    Livewire::test(CreateSample::class)
        ->fillForm([
            'type' => $type->value,
            'title' => 'Impact of Remote Work',
            'slug' => 'impact-of-remote-work',
            'short_description' => 'A short summary.',
            'sort_order' => 3,
            'is_published' => true,
        ])
        ->set('data.file_path', fakePdf('Remote Work Final.pdf'))
        ->call('create')
        ->assertHasNoFormErrors();

    $sample = Sample::sole();

    expect($sample->site->code)->toBe(SiteCode::Research->value)
        ->and($sample->type)->toBe($type)
        ->and($sample->sort_order)->toBe(3)
        ->and($sample->is_published)->toBeTrue()
        ->and($sample->file_name)->toBe('Remote Work Final.pdf')
        ->and($sample->file_path)->toStartWith('samples/documents/')
        ->and($sample->file_path)->not->toContain('Remote Work');

    Storage::disk('local')->assertExists($sample->file_path);
})->with(SampleType::cases());

it('rejects documents that are not pdf or word files', function () {
    $this->actingAs($this->admin);

    Livewire::test(CreateSample::class)
        ->fillForm([
            'type' => SampleType::Thesis->value,
            'title' => 'Bad upload',
            'slug' => 'bad-upload',
        ])
        ->set('data.file_path', UploadedFile::fake()->createWithContent('notes.txt', 'plain text'))
        ->call('create')
        ->assertHasFormErrors(['file_path']);

    expect(Sample::count())->toBe(0);
});

it('rejects documents above the size limit', function () {
    $this->actingAs($this->admin);

    Livewire::test(CreateSample::class)
        ->fillForm([
            'type' => SampleType::Thesis->value,
            'title' => 'Huge upload',
            'slug' => 'huge-upload',
        ])
        ->set('data.file_path', UploadedFile::fake()->create('huge.pdf', Sample::MAX_DOCUMENT_KILOBYTES + 1, 'application/pdf'))
        ->call('create')
        ->assertHasFormErrors(['file_path']);
});

it('requires slugs to be unique within the research website only', function () {
    Sample::factory()->create(['slug' => 'taken']);
    Sample::factory()->forSite(SiteCode::Publications)->create(['slug' => 'free-here']);

    $this->actingAs($this->admin);

    Livewire::test(CreateSample::class)
        ->fillForm(['type' => SampleType::Synopsis->value, 'title' => 'Taken', 'slug' => 'taken'])
        ->set('data.file_path', fakePdf())
        ->call('create')
        ->assertHasFormErrors(['slug' => 'unique']);

    Livewire::test(CreateSample::class)
        ->fillForm(['type' => SampleType::Synopsis->value, 'title' => 'Free here', 'slug' => 'free-here'])
        ->set('data.file_path', fakePdf())
        ->call('create')
        ->assertHasNoFormErrors();
});

it('edits a sample and replaces its document', function () {
    $sample = Sample::factory()->ofType(SampleType::Thesis)->withDocument()->create();
    $oldPath = $sample->file_path;

    $this->actingAs($this->admin);

    Livewire::test(EditSample::class, ['record' => $sample->getRouteKey()])
        ->fillForm(['title' => 'Updated title', 'type' => SampleType::Synopsis->value, 'is_published' => false])
        ->set('data.file_path', [fakePdf('replacement.pdf')])
        ->call('save')
        ->assertHasNoFormErrors();

    $sample->refresh();

    expect($sample->title)->toBe('Updated title')
        ->and($sample->type)->toBe(SampleType::Synopsis)
        ->and($sample->is_published)->toBeFalse()
        ->and($sample->file_name)->toBe('replacement.pdf')
        ->and($sample->file_path)->not->toBe($oldPath);

    Storage::disk('local')->assertExists($sample->file_path);
    Storage::disk('local')->assertMissing($oldPath);
});

it('publishes and unpublishes samples from the table', function () {
    $sample = Sample::factory()->unpublished()->create();

    $this->actingAs($this->admin);

    Livewire::test(ListSamples::class)->callTableBulkAction('publish', [$sample]);
    expect($sample->refresh()->is_published)->toBeTrue();

    Livewire::test(ListSamples::class)->callTableBulkAction('unpublish', [$sample]);
    expect($sample->refresh()->is_published)->toBeFalse();
});

it('reorders samples', function () {
    $first = Sample::factory()->create(['sort_order' => 1]);
    $second = Sample::factory()->create(['sort_order' => 2]);

    $this->actingAs($this->admin);

    Livewire::test(ListSamples::class)->call('reorderTable', [(string) $second->id, (string) $first->id]);

    expect($second->refresh()->sort_order)->toBeLessThan($first->refresh()->sort_order);
});

it('deletes a sample together with its stored files', function () {
    $sample = Sample::factory()->withDocument()->create();
    Storage::disk('public')->put('samples/thumbnails/cover.jpg', 'image');
    $sample->forceFill(['thumbnail' => 'samples/thumbnails/cover.jpg'])->saveQuietly();
    $path = $sample->file_path;

    $this->actingAs($this->admin);

    Livewire::test(ListSamples::class)->callTableAction('delete', $sample);

    expect(Sample::count())->toBe(0);
    Storage::disk('local')->assertMissing($path);
    Storage::disk('public')->assertMissing('samples/thumbnails/cover.jpg');
});

it('creates research services for the research website only', function () {
    $this->actingAs($this->admin);

    Livewire::test(CreateService::class)
        ->fillForm(['title' => 'Thesis Support', 'slug' => 'thesis-support', 'icon' => 'GraduationCap', 'tone' => 'ink', 'link' => '/services'])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Service::sole()->site_id)->toBe(Site::findByCode(SiteCode::Research)->id);
    $this->getJson('/api/research/services')->assertJsonCount(1, 'data');
    $this->getJson('/api/publication/services')->assertJsonCount(0, 'data');
});

it('saves research settings without touching publications settings', function () {
    SiteSetting::current(SiteCode::Publications)->update(['site_name' => 'RTS Publication']);

    $this->actingAs($this->admin);

    Livewire::test(ManageResearchSettings::class)
        ->fillForm(['site_name' => 'ThesisCraft Academy', 'contact_email' => 'hello@example.com'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(SiteSetting::current(SiteCode::Research)->site_name)->toBe('ThesisCraft Academy')
        ->and(SiteSetting::current(SiteCode::Publications)->site_name)->toBe('RTS Publication');
});
