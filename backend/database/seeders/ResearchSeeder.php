<?php

namespace Database\Seeders;

use App\Enums\SiteCode;
use App\Models\Faq;
use App\Models\Post;
use App\Models\SectionItem;
use App\Models\Service;
use App\Models\Site;
use App\Models\SitePage;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeds the roots_research website with the content that used to be
 * hardcoded in its React components, so the site looks the same once it
 * reads from the API. Safe to re-run: existing rows are never overwritten.
 */
class ResearchSeeder extends Seeder
{
    private Site $site;

    public function run(): void
    {
        $this->site = Site::findByCode(SiteCode::Research);

        $this->seedSettings();
        $this->seedPages();
        $this->seedServices();
        $this->seedSections();
        $this->seedTestimonials();
        $this->seedFaqs();
        $this->seedPosts();
    }

    private function seedSettings(): void
    {
        SiteSetting::firstOrCreate(['site_id' => $this->site->id], [
            'site_name' => 'ThesisCraft Academy',
            'tagline' => 'An elite, mentor-led thesis writing and research consultancy for PhD and Masters scholars — original work, delivered on time.',
            'footer_note' => 'Designed for original academic mentoring — not a substitute for your own scholarship.',
            'contact_email' => 'hello@thesiscraftacademy.com',
            'contact_phone' => '+91 12345 67890',
            'contact_address' => 'HSR Layout, Bengaluru, Karnataka, India',
            'facebook_url' => 'https://facebook.com',
            'twitter_url' => 'https://twitter.com',
            'instagram_url' => 'https://instagram.com',
            'linkedin_url' => 'https://linkedin.com',
        ]);
    }

    private function seedPages(): void
    {
        $defaultCta = [
            'cta_title' => 'Ready to Move Your Research Forward?',
            'cta_description' => 'Tell us about your thesis and get matched with a mentor within 24 hours.',
            'cta_label' => 'Begin Your Research',
        ];

        $pages = [
            'home' => [
                'eyebrow' => '100% Original · Mentor-Led · UGC-Aligned',
                'title' => 'Thesis Writing Services for PhD & Masters Students',
                'highlight' => 'PhD & Masters',
                'description' => 'An elite, end-to-end thesis consultancy — from topic selection to viva preparation — paired with verified subject-matter mentors, rigorous plagiarism checks and a delivery promise we keep.',
                'meta_title' => 'ThesisCraft Academy | Elite Thesis Writing & Research Consultancy',
                'meta_description' => 'ThesisCraft Academy — an elite, mentor-led thesis writing and research consultancy for PhD & Masters scholars.',
            ],
            'services' => [
                'eyebrow' => 'Our Services',
                'title' => 'Thesis Support, Built Around How You Work',
                'description' => 'Choose full-scope guidance or targeted help on a single chapter — every service is scoped, priced and scheduled before we begin.',
                ...$defaultCta,
            ],
            'research-support' => [
                'eyebrow' => 'Research Support',
                'title' => 'From Blank Page to Defensible Research Design',
                'description' => 'Every strong thesis starts with a strong research foundation — we help you build one that survives committee scrutiny.',
                'cta_title' => 'Stuck on Your Research Design?',
                'cta_description' => 'Get a free 20-minute consultation with a mentor in your subject area.',
                'cta_label' => 'Book a Free Consultation',
            ],
            'publication-support' => [
                'eyebrow' => 'Publication Support',
                'title' => 'Turn Your Thesis Into Published Research',
                'description' => 'From journal shortlisting to reviewer responses, we help your findings reach the audience they deserve.',
                'cta_title' => 'Ready to Publish Your Research?',
                'cta_description' => 'Share your thesis chapter and target journal — we\'ll map out a submission-ready plan.',
                'cta_label' => 'Start Publication Support',
            ],
            'mentors' => [
                'eyebrow' => 'Our Mentors',
                'title' => 'Learn From People Who\'ve Actually Done It',
                'description' => 'Every mentor on our roster holds an advanced degree, has published in their field, and has personally guided dozens of scholars to submission.',
                'cta_title' => 'Want to Mentor With Us?',
                'cta_description' => 'We\'re always looking for published researchers to join our verified mentor network.',
                'cta_label' => 'Apply to Mentor',
            ],
            'samples' => [
                'eyebrow' => 'Samples',
                'title' => 'Explore Our Research Samples',
                'highlight' => 'Research Samples',
                'description' => 'Browse sample research papers, theses and synopses prepared by our mentors — see the structure, depth and formatting standard you can expect.',
                'cta_title' => 'Want Work of This Standard?',
                'cta_description' => 'Tell us about your research and get matched with a mentor within 24 hours.',
                'cta_label' => 'Begin Your Research',
                'meta_description' => 'Sample research papers, theses and synopses from ThesisCraft Academy mentors.',
            ],
            'blog' => [
                'eyebrow' => 'The Journal',
                'title' => 'Research & Writing Insights',
                'description' => 'Practical, mentor-written articles on the craft of academic research, statistical analysis and thesis defence.',
                'cta_title' => 'Want Insights Like This In Your Inbox?',
                'cta_description' => 'Subscribe for a monthly digest of research-writing tips from our mentor network.',
                'cta_label' => 'Subscribe via Contact Form',
            ],
            'mock-viva' => [
                'eyebrow' => 'Mock Viva',
                'title' => 'Rehearse Your Defence Before It Counts',
                'description' => 'Panel-style mock vivas with examiner-grade questioning, live feedback and a recorded session you can review afterward.',
                'cta_title' => 'Don\'t Walk Into Your Viva Unprepared',
                'cta_description' => 'Book a mock viva session with a panel matched to your research area.',
                'cta_label' => 'View All Sessions',
            ],
            'workshops' => [
                'eyebrow' => 'Masterclasses & Workshops',
                'title' => 'Live Sessions to Sharpen Your Research Craft',
                'description' => 'Small-group, mentor-led workshops on the skills that make theses stronger — methodology, tools and academic writing.',
                'cta_title' => 'Seats Fill Up Fast',
                'cta_description' => 'Reserve your spot in our next live masterclass before registration closes.',
                'cta_label' => 'Reserve Your Seat',
            ],
            'about' => [
                'eyebrow' => 'About Us',
                'title' => 'We Exist to Make Original Research Less Lonely',
                'description' => 'ThesisCraft Academy was founded by researchers who remembered exactly how isolating a thesis can feel — and built the mentor network we wished we\'d had.',
                ...$defaultCta,
            ],
            'careers' => [
                'eyebrow' => 'Careers',
                'title' => 'Help Scholars Do Their Best Work',
                'description' => 'We\'re a distributed team of researchers, editors and analysts who care about academic integrity as much as we care about deadlines.',
                'cta_title' => 'Don\'t See the Right Role?',
                'cta_description' => 'We\'re always open to hearing from published researchers and academic editors.',
                'cta_label' => 'Send Us Your CV',
            ],
            'privacy' => [
                'eyebrow' => 'Privacy Policy',
                'title' => 'Your Privacy, Protected by Design',
                'description' => 'How ThesisCraft Academy collects, uses and protects your information.',
            ],
            'terms' => [
                'eyebrow' => 'Terms of Service',
                'title' => 'Clear Terms, No Fine-Print Surprises',
                'description' => 'The terms that govern your engagement with ThesisCraft Academy.',
            ],
        ];

        $names = config('sites.research.pages');

        foreach ($pages as $slug => $attributes) {
            $page = SitePage::firstOrCreate(
                ['site_id' => $this->site->id, 'slug' => $slug],
                [
                    'name' => $names[$slug],
                    'meta_title' => $slug === 'home' ? null : $names[$slug].' | ThesisCraft Academy',
                    ...$attributes,
                ],
            );

            // The legal pages show their last edit date as "Last updated".
            if ($page->wasRecentlyCreated && in_array($slug, ['privacy', 'terms'], true)) {
                $page->timestamps = false;
                $page->forceFill(['created_at' => '2026-03-01 00:00:00', 'updated_at' => '2026-03-01 00:00:00'])->save();
            }
        }
    }

    private function seedServices(): void
    {
        $services = [
            ['GraduationCap', 'Thesis Support', 'End-to-end guidance across topic finalisation, proposal writing, literature review, methodology design and final chapter compilation — tailored to your university\'s format.', 'ink', '/services'],
            ['BookMarked', 'Publication Support', 'Journal shortlisting, manuscript formatting, peer-review response drafting and Scopus/UGC-CARE aligned submission assistance from researchers who\'ve published before.', 'blue', '/publication-support'],
            ['LineChart', 'Analytical Services', 'Statistical design and execution in SPSS, R, Python and Excel — hypothesis testing, regression modelling, data visualisation and result interpretation you can defend.', 'blue', '/services'],
            ['Users2', 'Mentoring', 'One-on-one sessions with subject-matter mentors for direction reviews, viva preparation and ongoing accountability check-ins through every milestone.', 'ink', '/mentors'],
        ];

        foreach ($services as $index => [$icon, $title, $description, $tone, $link]) {
            Service::firstOrCreate(['site_id' => $this->site->id, 'slug' => Str::slug($title)], [
                'title' => $title,
                'description' => $description,
                'icon' => $icon,
                'tone' => $tone,
                'link' => $link,
                'sort_order' => $index,
            ]);
        }
    }

    private function seedSections(): void
    {
        $this->sectionItems('hero_stats', [
            ['title' => 'Theses Completed', 'value' => '750', 'suffix' => '+'],
            ['title' => 'Success Rate', 'value' => '98', 'suffix' => '%'],
            ['title' => 'Subject Experts', 'value' => '100', 'suffix' => '+'],
            ['title' => 'Years Experience', 'value' => '12', 'suffix' => '+'],
        ]);

        $this->sectionItems('ai_tags', [
            ['title' => 'Research Gap Detection', 'icon' => 'SearchCheck'],
            ['title' => 'Literature Mapping', 'icon' => 'Network'],
            ['title' => 'Citation Analysis', 'icon' => 'Quote'],
            ['title' => 'Smart Summarisation', 'icon' => 'FileStack'],
            ['title' => 'Plagiarism Insights', 'icon' => 'ShieldAlert'],
        ]);

        $this->sectionItems('ai_stats', [
            ['title' => 'Papers Indexed', 'icon' => 'Database', 'value' => '10', 'suffix' => 'M+'],
            ['title' => 'AI-Powered Engine', 'icon' => 'Zap', 'value' => '100', 'suffix' => '%', 'meta' => 'AI-Powered'],
            ['title' => 'Avg Query Speed', 'icon' => 'Timer', 'value' => '60', 'suffix' => 's'],
            ['title' => 'Academic Focus', 'icon' => 'Target', 'value' => '100', 'suffix' => '%'],
        ]);

        $this->sectionItems('why_choose_us', [
            ['title' => 'Expert Researchers', 'icon' => 'Sparkles', 'tone' => 'ink', 'meta' => '100+ mentors', 'description' => 'Every mentor holds an advanced degree in their field and has guided dozens of scholars through successful submissions.'],
            ['title' => 'Data Privacy & Confidentiality', 'icon' => 'Lock', 'tone' => 'blue', 'meta' => 'NDA protected', 'description' => 'Your research, identity and drafts are protected under strict NDAs — nothing is shared, resold or reused.'],
            ['title' => 'Plagiarism-Free & Original', 'icon' => 'ShieldCheck', 'tone' => 'blue', 'meta' => '0% tolerance', 'description' => 'Every deliverable passes Turnitin and iThenticate checks before it reaches you, backed by a written originality report.'],
            ['title' => 'On-Time Delivery', 'icon' => 'Clock3', 'tone' => 'ink', 'meta' => '98% on schedule', 'description' => 'Milestone-based timelines with buffer built in, so your submission window never becomes a source of stress.'],
        ]);

        $this->sectionItems('trust_badges', array_map(fn (string $title) => ['title' => $title], [
            'UGC-Compliant Process', 'Verified Subject Experts', 'Unlimited Revisions', '24/7 Support Desk', 'Money-Back Guarantee',
        ]));

        $this->sectionItems('process_steps', [
            ['title' => 'Discovery Call', 'description' => 'Share your research area, degree level and deadline. We map scope and match you with the right mentor.'],
            ['title' => 'Milestone Plan', 'description' => 'A transparent, chapter-by-chapter timeline with clear deliverables and review checkpoints.'],
            ['title' => 'Guided Drafting', 'description' => 'Your mentor writes, reviews or co-develops each section — plagiarism-checked at every stage.'],
            ['title' => 'Final Delivery & Viva Prep', 'description' => 'Polished, formatted submission plus optional mock viva rehearsal before your defence.'],
        ]);

        $this->sectionItems('research_offerings', [
            ['title' => 'Topic & Research Gap Identification', 'icon' => 'FileSearch', 'tone' => 'ink', 'description' => 'Structured brainstorming and AI-assisted literature scanning to land on a defensible, original research question.'],
            ['title' => 'Literature Review Development', 'icon' => 'BookText', 'tone' => 'blue', 'description' => 'Systematic reviews organised by theme or chronology, with proper citation management in APA, MLA, Chicago or your required style.'],
            ['title' => 'Methodology Design', 'icon' => 'GitBranch', 'tone' => 'ink', 'description' => 'Qualitative, quantitative or mixed-methods frameworks matched to your research questions and defensible under committee scrutiny.'],
            ['title' => 'Proposal & Synopsis Writing', 'icon' => 'FlaskConical', 'tone' => 'blue', 'description' => 'Committee-ready proposals with a clear problem statement, objectives, scope and timeline.'],
            ['title' => 'Data Collection Planning', 'icon' => 'BarChart3', 'tone' => 'ink', 'description' => 'Survey design, sampling strategy and instrument validation support before you go into the field.'],
            ['title' => 'Ethics & Compliance Review', 'icon' => 'ListChecks', 'tone' => 'blue', 'description' => 'Guidance through institutional review board documentation and informed-consent frameworks.'],
        ]);

        $this->sectionItems('publication_offerings', [
            ['title' => 'Journal Shortlisting', 'icon' => 'Search', 'tone' => 'ink', 'description' => 'Scopus, Web of Science and UGC-CARE indexed journal recommendations matched to your subject and scope.'],
            ['title' => 'Manuscript Conversion', 'icon' => 'Newspaper', 'tone' => 'blue', 'description' => 'Restructure thesis chapters into a publication-ready manuscript that meets target-journal formatting rules.'],
            ['title' => 'Formatting & Compliance', 'icon' => 'FileCheck2', 'tone' => 'ink', 'description' => 'Reference styling, figure/table formatting and submission checklist review before you hit submit.'],
            ['title' => 'Peer-Review Response Drafting', 'icon' => 'MessagesSquare', 'tone' => 'blue', 'description' => 'Structured, respectful responses to reviewer comments that improve your odds of acceptance.'],
            ['title' => 'Conference Paper Support', 'icon' => 'Award', 'tone' => 'ink', 'description' => 'Condense your research into conference-length papers and presentation-ready abstracts.'],
            ['title' => 'Open-Access & Copyright Guidance', 'icon' => 'Globe', 'tone' => 'blue', 'description' => 'Understand licensing options and avoid predatory journals with our vetted-publisher checklist.'],
        ]);

        $this->sectionItems('testimonial_stats', [
            ['title' => 'Expert Mentors', 'value' => '100', 'suffix' => '+'],
            ['title' => 'Active Students', 'value' => '2400', 'suffix' => '+'],
            ['title' => 'Research Domains', 'value' => '20', 'suffix' => '+'],
            ['title' => 'Success Rate', 'value' => '98', 'suffix' => '%'],
        ]);

        $this->sectionItems('mock_viva_sessions', [
            ['title' => 'Management & Business Studies Panel', 'subtitle' => 'Every Tuesday, 6:00 PM IST', 'meta' => '4 seats left', 'tone' => 'ink'],
            ['title' => 'Engineering & Computer Science Panel', 'subtitle' => 'Every Wednesday, 7:00 PM IST', 'meta' => '6 seats left', 'tone' => 'blue'],
            ['title' => 'Life Sciences & Health Panel', 'subtitle' => 'Every Thursday, 5:30 PM IST', 'meta' => '3 seats left', 'tone' => 'ink'],
            ['title' => 'Social Sciences & Education Panel', 'subtitle' => 'Every Friday, 6:30 PM IST', 'meta' => '8 seats left', 'tone' => 'blue'],
        ]);

        $this->sectionItems('workshops', [
            ['title' => 'Mastering Your Literature Review', 'subtitle' => 'Sat, Mar 21 · 5:00 PM IST', 'meta' => 'Dr. Ananya Kulkarni', 'tone' => 'blue'],
            ['title' => 'SPSS for First-Time Researchers', 'subtitle' => 'Sat, Mar 28 · 5:00 PM IST', 'meta' => 'Rohan Mehta', 'tone' => 'ink'],
            ['title' => 'Writing a Methodology That Holds Up', 'subtitle' => 'Sat, Apr 04 · 5:00 PM IST', 'meta' => 'Dr. Sanjay Verma', 'tone' => 'blue'],
            ['title' => 'Citation Management Deep-Dive', 'subtitle' => 'Sat, Apr 11 · 5:00 PM IST', 'meta' => 'Tarun Kapoor', 'tone' => 'ink'],
        ]);

        $this->sectionItems('global_cities', array_map(fn (array $city) => [
            'title' => $city[0], 'subtitle' => $city[1], 'meta' => $city[2], 'value' => $city[3],
        ], [
            ['New York', 'United States', '🇺🇸', 'Americas'],
            ['Toronto', 'Canada', '🇨🇦', 'Americas'],
            ['São Paulo', 'Brazil', '🇧🇷', 'Americas'],
            ['Mexico City', 'Mexico', '🇲🇽', 'Americas'],
            ['London', 'United Kingdom', '🇬🇧', 'Europe'],
            ['Berlin', 'Germany', '🇩🇪', 'Europe'],
            ['Paris', 'France', '🇫🇷', 'Europe'],
            ['Amsterdam', 'Netherlands', '🇳🇱', 'Europe'],
            ['Dublin', 'Ireland', '🇮🇪', 'Europe'],
            ['Mumbai', 'India', '🇮🇳', 'Asia'],
            ['Singapore', 'Singapore', '🇸🇬', 'Asia'],
            ['Tokyo', 'Japan', '🇯🇵', 'Asia'],
            ['Kuala Lumpur', 'Malaysia', '🇲🇾', 'Asia'],
            ['Seoul', 'South Korea', '🇰🇷', 'Asia'],
            ['Hong Kong', 'China', '🇭🇰', 'Asia'],
            ['Cape Town', 'South Africa', '🇿🇦', 'Africa'],
            ['Lagos', 'Nigeria', '🇳🇬', 'Africa'],
            ['Nairobi', 'Kenya', '🇰🇪', 'Africa'],
            ['Cairo', 'Egypt', '🇪🇬', 'Africa'],
            ['Sydney', 'Australia', '🇦🇺', 'Oceania'],
            ['Melbourne', 'Australia', '🇦🇺', 'Oceania'],
            ['Auckland', 'New Zealand', '🇳🇿', 'Oceania'],
            ['Dubai', 'United Arab Emirates', '🇦🇪', 'Middle East'],
            ['Doha', 'Qatar', '🇶🇦', 'Middle East'],
            ['Riyadh', 'Saudi Arabia', '🇸🇦', 'Middle East'],
            ['Abu Dhabi', 'United Arab Emirates', '🇦🇪', 'Middle East'],
        ]));

        $this->sectionItems('indian_cities', array_map(fn (string $city) => ['title' => $city], [
            'Mumbai', 'Delhi', 'Bengaluru', 'Hyderabad', 'Ahmedabad', 'Chennai',
            'Kolkata', 'Pune', 'Jaipur', 'Surat', 'Lucknow', 'Kanpur',
            'Nagpur', 'Indore', 'Thane', 'Bhopal', 'Visakhapatnam', 'Pimpri-Chinchwad',
            'Patna', 'Vadodara', 'Ghaziabad', 'Ludhiana', 'Agra', 'Nashik',
            'Faridabad', 'Meerut', 'Rajkot', 'Kalyan-Dombivli', 'Vasai-Virar', 'Varanasi',
            'Srinagar', 'Aurangabad', 'Dhanbad', 'Amritsar', 'Navi Mumbai', 'Allahabad',
            'Ranchi', 'Coimbatore', 'Jabalpur', 'Gwalior', 'Vijayawada', 'Jodhpur',
            'Madurai', 'Raipur', 'Kota', 'Guwahati', 'Chandigarh', 'Mysuru',
        ]));

        $this->sectionItems('contact_highlights', [
            ['title' => 'Quick Response', 'icon' => 'Zap', 'description' => 'We reply within 24 hours, every time.'],
            ['title' => 'Secure & Private', 'icon' => 'Lock', 'description' => 'Your details never leave our NDA-protected systems.'],
            ['title' => 'Expert Support', 'icon' => 'HeadphonesIcon', 'description' => 'Talk directly to a subject-matter mentor, not a call centre.'],
        ]);

        $this->sectionItems('about_values', [
            ['title' => 'Outcome-Focused', 'icon' => 'Target', 'description' => 'We measure success by your submission and defence outcome, not billable hours.'],
            ['title' => 'Student-First', 'icon' => 'Heart', 'description' => 'Every process decision starts with what actually reduces your stress and improves your work.'],
            ['title' => 'Academic Integrity', 'icon' => 'Compass', 'description' => 'We guide and mentor — the understanding, and the credit, stays yours.'],
        ]);

        $this->sectionItems('about_stats', [
            ['title' => 'Years in Academic Mentoring', 'value' => '12', 'suffix' => '+'],
            ['title' => 'Scholars Guided', 'value' => '2400', 'suffix' => '+'],
            ['title' => 'Countries Served', 'value' => '20', 'suffix' => '+'],
        ]);

        $this->sectionItems('careers_openings', [
            ['title' => 'PhD Research Mentor — Management', 'subtitle' => 'Contract · Remote', 'meta' => 'Global'],
            ['title' => 'PhD Research Mentor — Engineering', 'subtitle' => 'Contract · Remote', 'meta' => 'Global'],
            ['title' => 'Statistical Analyst (SPSS/R/Python)', 'subtitle' => 'Part-time · Remote', 'meta' => 'Global'],
            ['title' => 'Academic Content Editor', 'subtitle' => 'Full-time · Remote', 'meta' => 'India'],
            ['title' => 'Student Success Coordinator', 'subtitle' => 'Full-time · Hybrid', 'meta' => 'Bengaluru, India'],
        ]);

        $this->sectionItems('privacy_sections', [
            ['title' => '1. Information We Collect', 'description' => 'We collect only what\'s necessary to match you with a mentor and deliver your service — your name, contact details, academic level, subject area and the documents you choose to share with us.'],
            ['title' => '2. How We Use Your Information', 'description' => 'Your information is used solely to coordinate mentoring, process your requests and communicate updates about your engagement. We never sell or rent your data to third parties.'],
            ['title' => '3. Confidentiality of Your Research', 'description' => 'All research materials, drafts and personal details shared with mentors are covered under a standing non-disclosure agreement. Mentors may not reuse, publish or share your work.'],
            ['title' => '4. Data Storage & Security', 'description' => 'Documents and communications are stored on access-controlled systems with encryption at rest and in transit. Access is limited to your assigned mentor and support team.'],
            ['title' => '5. Cookies & Analytics', 'description' => 'Our website uses minimal, privacy-respecting analytics to understand site usage. No personally identifying data is sold to advertising networks.'],
            ['title' => '6. Your Rights', 'description' => 'You may request a copy of your data, ask us to correct inaccuracies, or request deletion of your records at any time by contacting our support team.'],
            ['title' => '7. Changes to This Policy', 'description' => 'We\'ll notify active clients of material changes to this policy via email at least 14 days before they take effect.'],
            ['title' => '8. Contact Us', 'description' => 'Questions about this policy can be sent to hello@thesiscraftacademy.com and we\'ll respond within two business days.'],
        ]);

        $this->sectionItems('terms_sections', [
            ['title' => '1. Nature of Our Services', 'description' => 'ThesisCraft Academy provides academic mentoring, research guidance, editing and consultation services. We do not guarantee grades, admission decisions or publication acceptance, which remain at the discretion of your institution or target journal.'],
            ['title' => '2. Academic Integrity', 'description' => 'Our services are intended to support and develop your own research and writing skills. You remain responsible for ensuring your final submission complies with your institution\'s academic integrity policies.'],
            ['title' => '3. Engagement & Payment', 'description' => 'Scope, pricing and timelines are agreed in writing before work begins. Payment milestones are tied to deliverables, and any changes to scope will be quoted separately before proceeding.'],
            ['title' => '4. Revisions', 'description' => 'Unlimited revisions are included within the agreed scope for up to 30 days after final delivery. Requests outside the original scope may be quoted as an additional service.'],
            ['title' => '5. Plagiarism-Free Guarantee', 'description' => 'Every written deliverable is checked against Turnitin or iThenticate prior to delivery. If a substantiated plagiarism issue is found within the delivered material, we will correct it at no additional cost.'],
            ['title' => '6. Cancellations & Refunds', 'description' => 'Cancellations made before work begins on a milestone are eligible for a full refund of that milestone. Partial refunds may apply once work is underway, calculated pro-rata to completed work.'],
            ['title' => '7. Intellectual Property', 'description' => 'Upon full payment, all rights to the delivered material transfer to you. ThesisCraft Academy retains no ownership claim over your finished work.'],
            ['title' => '8. Limitation of Liability', 'description' => 'ThesisCraft Academy is not liable for academic outcomes, institutional decisions or third-party actions beyond the direct scope of the services we provide.'],
            ['title' => '9. Governing Law', 'description' => 'These terms are governed by the laws of the jurisdiction in which ThesisCraft Academy is registered, without regard to conflict-of-law principles.'],
        ]);
    }

    private function seedTestimonials(): void
    {
        $testimonials = [
            ['Sudeep R.', 'PhD Candidate, Management', 'My mentor didn\'t just polish sentences — she helped me rethink my entire methodology chapter until it actually held up under committee questioning.', 5],
            ['Farah A.', 'Masters, Public Health', 'The literature mapping tool alone saved me three weeks of manual searching. Pair that with a human mentor and it\'s a completely different experience.', 5],
            ['Vikram S.', 'PhD, Computer Science', 'Turnaround was exactly as promised, revisions were unlimited and genuinely fast, and the plagiarism report gave me real peace of mind before submission.', 5],
            ['Meera J.', 'Masters, Social Work', 'The mock viva session was uncannily close to my real defence. I walked in prepared for questions I hadn\'t even thought to ask myself.', 5],
            ['Aditya N.', 'PhD, Environmental Science', 'Confidentiality was my biggest worry going in. Every interaction was under NDA and my advisor never suspected a thing — because it was still my work, just better guided.', 4],
        ];

        foreach ($testimonials as $index => [$name, $role, $quote, $rating]) {
            Testimonial::firstOrCreate(['site_id' => $this->site->id, 'name' => $name], [
                'role' => $role,
                'quote' => $quote,
                'rating' => $rating,
                'sort_order' => $index,
            ]);
        }
    }

    private function seedFaqs(): void
    {
        $faqs = [
            ['How do I get started with ThesisCraft Academy?', 'Share your research area, degree level and deadline through our contact form or a quick discovery call. We\'ll match you with a mentor within 24 hours and map out a milestone plan before any work begins.'],
            ['Do you offer full writing or only guidance?', 'Both. Some scholars want a mentor to review and refine their own drafts; others need structured, chapter-by-chapter writing support. You choose the level of involvement, and it can change at any stage.'],
            ['Is it legal and ethical to use a thesis writing service?', 'Yes — academic mentoring, editing and research consultation are widely used and permitted as long as the final submission reflects your own understanding. We position ourselves as a guidance and skill-building partner, consistent with most university academic integrity policies.'],
            ['Can you guarantee my work will be plagiarism-free?', 'Every deliverable is checked against Turnitin and iThenticate before delivery, and you receive the similarity report alongside your document. We write from primary sources and proper citation, never from resold templates.'],
            ['What is your typical turnaround time?', 'It depends on scope — a literature review chapter usually takes 7–10 days, while a full thesis is scheduled across milestones aligned to your submission date. Rush timelines are available for select services.'],
            ['How many revisions are included?', 'Unlimited revisions within the agreed scope until you\'re satisfied, for up to 30 days after final delivery. We also support post-viva or post-review amendments as a separate add-on.'],
            ['Can you help with just my synopsis or research proposal?', 'Absolutely — synopsis and proposal development is one of our most requested services, including problem statement framing, objective setting and a defensible methodology outline.'],
            ['How is pricing determined?', 'Pricing depends on academic level, subject complexity, word count and timeline. You\'ll receive a transparent, itemised quote before committing — no hidden fees or surprise add-ons later.'],
            ['How confidential is my information?', 'Strictly confidential. All client and mentor communication happens under NDA, drafts are stored on access-controlled systems, and nothing is shared with third parties or reused for other clients.'],
            ['Do you support journal publication after the thesis is done?', 'Yes — our publication support team helps convert thesis chapters into journal-ready manuscripts, recommends suitable indexed journals, and assists through submission and reviewer responses.'],
        ];

        foreach ($faqs as $index => [$question, $answer]) {
            Faq::firstOrCreate(['site_id' => $this->site->id, 'question' => $question], [
                'answer' => $answer,
                'sort_order' => $index,
            ]);
        }
    }

    private function seedPosts(): void
    {
        $posts = [
            ['How to Frame a Research Gap Reviewers Won\'t Question', 'A disciplined, five-step method for identifying and articulating a defensible research gap in your introduction chapter.', 'Dr. Ananya Kulkarni', '2026-03-14', '6 min read', 'Research Writing'],
            ['Choosing Between SPSS, R and Python for Your Data Chapter', 'A considered comparison of statistical tools by discipline, sample size and the kind of analysis your study actually demands.', 'Rohan Mehta', '2026-02-27', '8 min read', 'Data Analysis'],
            ['Surviving Your Viva Voce: Questions Examiners Actually Ask', 'Insights from 200+ mock viva sessions on the question patterns, follow-ups and defensive strategies that matter most.', 'Dr. Priya Nair', '2026-02-09', '5 min read', 'Viva Preparation'],
            ['Writing a Methodology Chapter Your Committee Won\'t Push Back On', 'How to justify your research design choices with evidence, precedent and clear alignment to your research questions.', 'Dr. Sanjay Verma', '2026-01-22', '7 min read', 'Methodology'],
            ['Citation Managers Compared: Zotero, Mendeley and EndNote', 'A hands-on breakdown of the three most-used citation tools, and which one fits your workflow and university\'s style guide.', 'Tarun Kapoor', '2026-01-08', '6 min read', 'Tools'],
            ['From Thesis to Journal Article in Six Weeks', 'A realistic timeline for converting a completed thesis chapter into a submission-ready manuscript, milestone by milestone.', 'Dr. Leela Fernandes', '2025-12-19', '9 min read', 'Publication'],
        ];

        foreach ($posts as [$title, $excerpt, $author, $date, $readTime, $tag]) {
            Post::firstOrCreate(['site_id' => $this->site->id, 'slug' => Str::slug($title)], [
                'title' => $title,
                'excerpt' => $excerpt,
                'author' => $author,
                'category' => $tag,
                'read_time' => $readTime,
                'published_at' => $date,
            ]);
        }
    }

    /**
     * @param  list<array<string, string>>  $items
     */
    private function sectionItems(string $section, array $items): void
    {
        foreach ($items as $index => $attributes) {
            SectionItem::firstOrCreate(
                ['site_id' => $this->site->id, 'section' => $section, 'title' => $attributes['title']],
                [...$attributes, 'sort_order' => $index],
            );
        }
    }
}
