<?php

namespace Database\Seeders;

use App\Enums\SiteCode;
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
use Illuminate\Database\Seeder;

/**
 * Seeds the roots_it website with the content that used to be hardcoded in
 * its React data files (exported to data/it-content.json) and page
 * components, so the site looks the same once it reads from the API.
 * Safe to re-run: existing rows are never overwritten.
 */
class ItSeeder extends Seeder
{
    private Site $site;

    /**
     * @var array<string, mixed>
     */
    private array $data;

    public function run(): void
    {
        $this->site = Site::findByCode(SiteCode::It);
        $this->data = json_decode(file_get_contents(__DIR__.'/data/it-content.json'), true, flags: JSON_THROW_ON_ERROR);

        $this->seedSettings();
        $this->seedPages();
        $this->seedServices();
        $this->seedSections();
        $this->seedProjects();
        $this->seedCaseStudies();
        $this->seedTestimonials();
        $this->seedFaqs();
        $this->seedPosts();
    }

    private function seedSettings(): void
    {
        $site = $this->data['site'];
        $socials = collect($site['socials'])->pluck('href', 'icon');

        SiteSetting::firstOrCreate(['site_id' => $this->site->id], [
            'site_name' => $site['name'],
            'tagline' => $site['tagline'],
            'footer_text' => 'We build web & mobile products, custom software and full-funnel marketing for ambitious teams.',
            'footer_note' => $site['copyright'],
            'founded_year' => $site['foundedYear'],
            'contact_email' => $site['email'],
            'contact_phone' => $site['phone'],
            'contact_address' => trim($site['address']['line1'])."\n".trim($site['address']['line2']),
            'contact_hours' => $site['workingHours'],
            'map_query' => $site['mapQuery'],
            'linkedin_url' => $socials['linkedin'] ?? null,
            'instagram_url' => $socials['instagram'] ?? null,
            'facebook_url' => $socials['facebook'] ?? null,
            'twitter_url' => $socials['x'] ?? null,
        ]);
    }

    private function seedPages(): void
    {
        $pages = [
            'home' => [
                'eyebrow' => 'Software Development & Digital Marketing',
                'title' => 'Transform Your Ideas Into Powerful Digital Solutions',
                'highlight' => 'Powerful Digital Solutions',
                'description' => 'Roots Technology helps businesses grow with innovative software development, web and mobile applications, custom software, e-commerce solutions and full-funnel digital marketing.',
                'meta_description' => 'Roots Technology helps businesses grow with software development, web & mobile apps, custom software, e-commerce and digital marketing services.',
                'cta_title' => 'Let’s Build Something Amazing Together',
                'cta_label' => 'Start Your Project',
                'cta_secondary_label' => 'Get Free Consultation',
            ],
            'about' => [
                'eyebrow' => 'About Roots Technology',
                'title' => 'A team that builds like it’s their own company',
                'description' => 'We started Roots Technology in 2021 with a simple idea: pair senior engineering with honest marketing, and treat every client’s goals as our own.',
                'meta_title' => 'About Us',
                'meta_description' => 'Roots Technology is a remote-first software development and digital marketing agency founded in 2021. Meet the team and the values behind the work.',
                'cta_title' => 'Let’s see if we’re a fit',
                'cta_label' => 'Get a Free Consultation',
                'cta_secondary_label' => 'View Our Work',
            ],
            'services' => [
                'eyebrow' => 'Services',
                'title' => 'Everything you need to build and grow a digital product',
                'description' => 'Two practices, one team. Engineering that ships reliable software, and marketing that turns it into measurable growth.',
                'meta_title' => 'Services',
                'meta_description' => 'Roots Technology services: website & software development, mobile apps, custom software, e-commerce, SEO, social media, paid advertising and full-funnel digital marketing.',
                'cta_title' => 'Not sure which service you need?',
                'cta_description' => 'Book a free consultation. We’ll listen, ask the right questions and point you in the right direction — even if that’s not us.',
                'cta_label' => 'Get a Free Consultation',
                'cta_secondary_label' => 'Contact Us',
            ],
            'portfolio' => [
                'eyebrow' => 'Portfolio',
                'title' => 'Work we’ve shipped and stand behind',
                'description' => 'Filter by discipline to see how we approach different kinds of problems.',
                'meta_title' => 'Portfolio',
                'meta_description' => 'Selected work from Roots Technology — web platforms, mobile apps, custom software, e-commerce stores and digital marketing programmes.',
                'cta_title' => 'Have a project like these in mind?',
                'cta_label' => 'Start Your Project',
                'cta_secondary_label' => 'Read Case Studies',
            ],
            'case-studies' => [
                'eyebrow' => 'Case Studies',
                'title' => 'The problem, the approach, the numbers',
                'description' => 'A closer look at how specific engagements moved specific metrics. Figures are representative placeholder data.',
                'meta_title' => 'Case Studies',
                'meta_description' => 'In-depth case studies from Roots Technology — the client challenge, our solution, the technologies used and the measurable results.',
                'cta_title' => 'Want results like these?',
                'cta_label' => 'Get a Free Consultation',
                'cta_secondary_label' => 'See the Portfolio',
            ],
            'blog' => [
                'eyebrow' => 'Blog',
                'title' => 'Notes from the team',
                'description' => 'Practical, opinionated writing on building and growing digital products. No fluff, no listicles.',
                'meta_title' => 'Blog',
                'meta_description' => 'Practical articles on web and software development, mobile apps, SEO and digital marketing from the Roots Technology team.',
                'cta_title' => 'Prefer to talk it through?',
                'cta_description' => 'If an article raised a question about your own project, we’re happy to get on a call.',
                'cta_label' => 'Talk to an Expert',
                'cta_secondary_label' => 'View Services',
            ],
            'contact' => [
                'eyebrow' => 'Contact',
                'title' => 'Let’s Discuss Your Project',
                'description' => 'Tell us what you’re trying to build or grow. We’ll reply within one business day with next steps — or a straight answer if we’re not the right fit.',
                'meta_title' => 'Contact',
                'meta_description' => 'Talk to Roots Technology about your project. Get a free consultation, request a quote or ask us anything — we reply within one business day.',
            ],
        ];

        foreach ($this->data['servicePages'] as $servicePage) {
            $pages[$servicePage['slug']] = [
                'eyebrow' => $servicePage['eyebrow'],
                'title' => $servicePage['title'],
                'description' => $servicePage['subtitle'],
                'meta_title' => $servicePage['seoTitle'],
                'meta_description' => $servicePage['seoDescription'],
                'cta_title' => $servicePage['ctaTitle'],
                'cta_label' => 'Start Your Project',
                'cta_secondary_label' => 'Request a Quote',
                'content' => $servicePage['content'],
            ];
        }

        $names = config('sites.it.pages');

        foreach ($pages as $slug => $attributes) {
            SitePage::firstOrCreate(
                ['site_id' => $this->site->id, 'slug' => $slug],
                ['name' => $names[$slug], ...$attributes],
            );
        }
    }

    private function seedServices(): void
    {
        foreach ($this->data['services'] as $index => $service) {
            Service::firstOrCreate(['site_id' => $this->site->id, 'slug' => $service['slug']], [
                'title' => $service['title'],
                'category' => $service['category'],
                'description' => $service['description'],
                'icon' => $service['icon'],
                'link' => $service['link'],
                'sort_order' => $index,
            ]);
        }
    }

    private function seedSections(): void
    {
        $data = $this->data;

        $this->sectionItems('stats', array_map(fn (array $stat) => [
            'title' => $stat['label'], 'value' => (string) $stat['value'], 'suffix' => $stat['suffix'],
        ], $data['stats']));

        $this->sectionItems('hero_trust', [['title' => 'Trusted by 50+ founders & product teams worldwide']]);

        $this->sectionItems('hero_avatars', array_map(fn (string $seed) => [
            'title' => "Client {$seed}", 'image' => 'https://i.pravatar.cc/160?u='.$seed,
        ], ['h1', 'h2', 'h3', 'h4']));

        $this->sectionItems('client_logos', array_map(fn (string $name) => ['title' => $name], [
            'NorthPeak', 'Lumen', 'CareLoop', 'Meridian', 'Atlas Legal', 'GreenRoute',
        ]));

        $this->sectionItems('process_steps', array_map(fn (array $step) => [
            'title' => $step['title'], 'description' => $step['description'], 'icon' => $step['icon'],
        ], $data['process']));

        $this->sectionItems('why_choose_us', array_map(fn (array $item) => [
            'title' => $item['title'], 'description' => $item['description'], 'icon' => $item['icon'],
        ], $data['whyChooseUs']));

        $this->sectionItems('mission_vision', [
            ['title' => 'Our Mission', 'description' => $data['mission'], 'icon' => 'target'],
            ['title' => 'Our Vision', 'description' => $data['vision'], 'icon' => 'eye'],
        ]);

        $this->sectionItems('values', array_map(fn (array $value) => [
            'title' => $value['title'], 'description' => $value['description'], 'icon' => $value['icon'],
        ], $data['values']));

        $this->sectionItems('about_intro', [[
            'title' => 'Engineers, designers and marketers under one roof',
            'description' => "Roots Technology is a {years}-year-old digital agency of {team_count}+ specialists. We design, build and grow web platforms, mobile apps, custom software and e-commerce experiences for founders and product teams around the world.\n\nWe’re deliberately small and senior. You work directly with the people doing the work — no account-manager telephone game, no juniors learning on your budget.",
            'image' => 'https://picsum.photos/seed/about-team/900/700',
        ]]);

        $this->sectionItems('about_culture', [[
            'title' => 'How we operate as a team',
            'image' => 'https://picsum.photos/seed/about-culture/900/700',
        ]]);

        $this->sectionItems('culture_points', array_map(fn (string $point) => ['title' => $point], $data['culture']));

        $this->sectionItems('team', array_map(fn (array $member) => [
            'title' => $member['name'], 'subtitle' => $member['role'], 'image' => $member['avatar'],
        ], $data['team']));

        $this->sectionItems('technologies', array_map(fn (array $tech) => [
            'title' => $tech['name'], 'subtitle' => $tech['group'], 'value' => $tech['icon'],
        ], $data['technologies']));

        $this->sectionItems('project_categories', array_map(
            fn (string $category) => ['title' => $category],
            array_values(array_diff($data['projectCategories'], ['All'])),
        ));

        $this->sectionItems('blog_categories', array_map(
            fn (string $category) => ['title' => $category],
            array_values(array_diff($data['blogCategories'], ['All'])),
        ));

        $this->sectionItems('case_study_metrics', [
            ['title' => 'Organic traffic growth on growth programmes', 'value' => '200%+'],
            ['title' => 'Typical increase in qualified leads', 'value' => '150%'],
            ['title' => 'Average performance improvement on rebuilds', 'value' => '40%'],
        ]);

        $this->sectionItems('contact_service_options', array_map(fn (string $option) => ['title' => $option], $data['serviceOptions']));
        $this->sectionItems('contact_budget_options', array_map(fn (string $option) => ['title' => $option], $data['budgetOptions']));
    }

    private function seedProjects(): void
    {
        foreach ($this->data['projects'] as $index => $project) {
            Project::firstOrCreate(['site_id' => $this->site->id, 'slug' => $project['slug']], [
                'name' => $project['name'],
                'category' => $project['category'],
                'industry' => $project['industry'],
                'technologies' => $project['technologies'],
                'description' => $project['description'],
                'image' => $project['image'],
                'year' => $project['year'],
                'is_featured' => $project['featured'],
                'sort_order' => $index,
            ]);
        }
    }

    private function seedCaseStudies(): void
    {
        foreach ($this->data['caseStudies'] as $index => $caseStudy) {
            CaseStudy::firstOrCreate(['site_id' => $this->site->id, 'slug' => $caseStudy['slug']], [
                'title' => $caseStudy['title'],
                'client' => $caseStudy['client'],
                'industry' => $caseStudy['industry'],
                'image' => $caseStudy['image'],
                'challenge' => $caseStudy['challenge'],
                'solution' => $caseStudy['solution'],
                'technologies' => $caseStudy['technologies'],
                'results' => $caseStudy['results'],
                'sort_order' => $index,
            ]);
        }
    }

    private function seedTestimonials(): void
    {
        foreach ($this->data['testimonials'] as $index => $testimonial) {
            Testimonial::firstOrCreate(['site_id' => $this->site->id, 'name' => $testimonial['name']], [
                'role' => $testimonial['role'],
                'company' => $testimonial['company'],
                'avatar' => $testimonial['avatar'],
                'quote' => $testimonial['quote'],
                'rating' => $testimonial['rating'],
                'sort_order' => $index,
            ]);
        }
    }

    private function seedFaqs(): void
    {
        foreach ($this->data['faqs'] as $index => $faq) {
            Faq::firstOrCreate(['site_id' => $this->site->id, 'question' => $faq['q']], [
                'answer' => $faq['a'],
                'sort_order' => $index,
            ]);
        }
    }

    private function seedPosts(): void
    {
        foreach ($this->data['blogs'] as $blog) {
            Post::firstOrCreate(['site_id' => $this->site->id, 'slug' => $blog['slug']], [
                'title' => $blog['title'],
                'category' => $blog['category'],
                'author' => $blog['author'],
                'excerpt' => $blog['excerpt'],
                'image' => $blog['image'],
                'body' => $blog['body'],
                'published_at' => $blog['date'],
            ]);
        }
    }

    /**
     * Items are matched on section + title + subtitle, so re-running the
     * seeder never duplicates them (and two technologies with the same name
     * in different groups stay separate).
     *
     * @param  list<array<string, mixed>>  $items
     */
    private function sectionItems(string $section, array $items): void
    {
        foreach ($items as $index => $attributes) {
            SectionItem::firstOrCreate(
                [
                    'site_id' => $this->site->id,
                    'section' => $section,
                    'title' => $attributes['title'],
                    'subtitle' => $attributes['subtitle'] ?? null,
                ],
                [...$attributes, 'sort_order' => $index],
            );
        }
    }
}
