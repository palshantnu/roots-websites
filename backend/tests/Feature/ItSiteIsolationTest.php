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
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Creates one record per site for every shared, site-scoped model, each
 * titled after its site, so a response containing another site's label
 * proves a leak.
 */
beforeEach(function () {
    foreach ([SiteCode::Publications, SiteCode::Research, SiteCode::It] as $site) {
        $label = $site->value;
        Service::factory()->forSite($site)->create(['title' => "{$label} service", 'slug' => 'shared-slug']);
        Faq::factory()->forSite($site)->create(['question' => "{$label} question?"]);
        Post::factory()->forSite($site)->create(['title' => "{$label} post", 'slug' => 'shared-post']);
        SitePage::factory()->forSite($site)->create(['slug' => 'home', 'title' => "{$label} page"]);
        SectionItem::factory()->forSite($site)->create(['section' => 'stats', 'title' => "{$label} stat"]);
        Testimonial::factory()->forSite($site)->create(['name' => "{$label} client"]);
        Project::factory()->forSite($site)->create(['name' => "{$label} project", 'slug' => 'shared-project']);
        CaseStudy::factory()->forSite($site)->create(['title' => "{$label} case", 'slug' => 'shared-case']);
        SiteSetting::current($site)->update(['site_name' => "{$label} site"]);
    }
});

dataset('it endpoints', [
    '/api/it/services', '/api/it/services/shared-slug', '/api/it/faqs', '/api/it/posts', '/api/it/posts/shared-post',
    '/api/it/pages', '/api/it/pages/home', '/api/it/sections', '/api/it/testimonials', '/api/it/projects',
    '/api/it/projects/shared-project', '/api/it/case-studies', '/api/it/case-studies/shared-case', '/api/it/settings',
]);

it('only ever returns it content from it endpoints', function (string $url) {
    foreach (['', '?site=publications', '?site=research', '?site_id=1', '?site_id=2', '?site_code=research&site=publications'] as $query) {
        $body = $this->getJson($url.$query)->assertOk()->getContent();

        expect($body)->toContain('it ')
            ->not->toContain('publications ')
            ->not->toContain('research ');
    }
})->with('it endpoints');

it('never returns it content from research endpoints', function (string $url) {
    foreach (['', '?site=it', '?site_id=3'] as $query) {
        $body = $this->getJson($url.$query)->assertOk()->getContent();

        expect($body)->toContain('research ')->not->toContain('"it ')->not->toContain('publications ');
    }
})->with(['/api/research/services', '/api/research/faqs', '/api/research/posts', '/api/research/pages', '/api/research/sections', '/api/research/testimonials', '/api/research/settings']);

it('never returns it content from publication endpoints', function (string $url) {
    foreach (['', '?site=it', '?site_id=3'] as $query) {
        $body = $this->getJson($url.$query)->assertOk()->getContent();

        expect($body)->toContain('publications ')->not->toContain('"it ')->not->toContain('research ');
    }
})->with(['/api/publication/services', '/api/publication/faqs', '/api/publication/posts', '/api/publication/settings']);

it('does not expose it-only modules on other sites', function (string $url) {
    $this->getJson($url)->assertNotFound();
})->with([
    '/api/research/projects', '/api/research/case-studies', '/api/publication/projects',
    '/api/publication/case-studies', '/api/publication/pages', '/api/publication/sections',
]);
