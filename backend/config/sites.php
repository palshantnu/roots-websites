<?php

/*
|--------------------------------------------------------------------------
| Website content configuration
|--------------------------------------------------------------------------
|
| Per-site lists used by the admin panel. Keys are site codes (see
| App\Enums\SiteCode). Add an `it` entry here when roots_it becomes dynamic.
|
| `sections` - repeatable homepage/page lists editable under "Sections".
|              key => [label, help text shown in the admin form]
| `pages`    - page slugs the frontend asks for (hero, CTA and SEO copy).
| `icons`    - icon names the frontend can render. These must match the
|              icon registry of the website (roots_research: src/lib/icons.js).
| `tones`    - card colour variants supported by the website's design.
|
*/

return [

    'research' => [

        'sections' => [
            'hero_stats' => ['Home: hero stats', 'Title = label, Value = number, Suffix = e.g. "+" or "%".'],
            'ai_tags' => ['Home: AI assistant tags', 'Title = tag label, Icon.'],
            'ai_stats' => ['Home: AI assistant stats', 'Title = label, Icon, Value + Suffix. Put text in Meta to show it instead of a counter.'],
            'why_choose_us' => ['Why choose us cards', 'Title, Description, Icon, Tone, Meta = stat pill (e.g. "100+ mentors").'],
            'trust_badges' => ['Why choose us: trust badges', 'Title = badge text.'],
            'process_steps' => ['How it works steps', 'Title, Description. Steps are numbered by display order.'],
            'research_offerings' => ['Research support offerings', 'Title, Description, Icon, Tone.'],
            'publication_offerings' => ['Publication support offerings', 'Title, Description, Icon, Tone.'],
            'testimonial_stats' => ['Testimonials: stats', 'Title = label, Value = number, Suffix.'],
            'mock_viva_sessions' => ['Mock viva sessions', 'Title, Subtitle = schedule, Meta = seats left, Tone.'],
            'workshops' => ['Workshops', 'Title, Subtitle = date and time, Meta = host, Tone.'],
            'global_cities' => ['Global presence cities', 'Title = city, Subtitle = country, Meta = flag emoji, Value = region.'],
            'indian_cities' => ['Local support cities', 'Title = city name.'],
            'contact_highlights' => ['Contact: why reach out', 'Title, Description, Icon.'],
            'about_values' => ['About: values', 'Title, Description, Icon.'],
            'about_stats' => ['About: stats', 'Title = label, Value = number, Suffix.'],
            'careers_openings' => ['Careers: open roles', 'Title = role, Subtitle = type, Meta = location.'],
            'privacy_sections' => ['Privacy policy sections', 'Title = heading, Description = body text.'],
            'terms_sections' => ['Terms of service sections', 'Title = heading, Description = body text.'],
        ],

        'pages' => [
            'home' => 'Home',
            'services' => 'Services',
            'research-support' => 'Research Support',
            'publication-support' => 'Publication Support',
            'mentors' => 'Mentors',
            'samples' => 'Samples',
            'blog' => 'Blog',
            'mock-viva' => 'Mock Viva',
            'workshops' => 'Workshops',
            'about' => 'About',
            'careers' => 'Careers',
            'privacy' => 'Privacy Policy',
            'terms' => 'Terms of Service',
        ],

        'icons' => [
            'Award', 'BadgeCheck', 'BarChart3', 'BookMarked', 'BookOpen', 'BookText', 'Briefcase',
            'CalendarClock', 'CheckCircle2', 'ClipboardCheck', 'Clock3', 'Compass', 'Database',
            'FileCheck2', 'FileSearch', 'FileStack', 'FileText', 'FlaskConical', 'GitBranch', 'Globe',
            'GraduationCap', 'HeadphonesIcon', 'Heart', 'Layers', 'LineChart', 'ListChecks', 'Lock',
            'MessagesSquare', 'Mic', 'Network', 'Newspaper', 'PenTool', 'Quote', 'Search', 'SearchCheck',
            'ShieldAlert', 'ShieldCheck', 'Sparkles', 'Target', 'Timer', 'Users2', 'Zap',
        ],

        'tones' => [
            'ink' => 'Light (white with blue border)',
            'blue' => 'Blue (filled gradient)',
        ],

    ],

    'it' => [

        'sections' => [
            'stats' => ['Company statistics', 'Title = label, Value = number, Suffix = e.g. "+".'],
            'hero_trust' => ['Home: hero trust line', 'Title = text shown next to the avatars.'],
            'hero_avatars' => ['Home: hero avatars', 'Title = name (alt text), Image = photo.'],
            'client_logos' => ['Home: client names strip', 'Title = client name.'],
            'process_steps' => ['Development process steps', 'Title, Description. Steps are numbered by display order.'],
            'why_choose_us' => ['Why choose us', 'Title, Description, Icon.'],
            'mission_vision' => ['About: mission & vision', 'Title (e.g. Our Mission), Description, Icon.'],
            'values' => ['About: values', 'Title, Description, Icon.'],
            'about_intro' => ['About: who we are', 'Title = heading, Description = text (blank line between paragraphs; {years} and {team_count} are filled in), Image.'],
            'about_culture' => ['About: work culture block', 'Title = heading, Image.'],
            'culture_points' => ['About: work culture points', 'Title = point.'],
            'team' => ['About: team members', 'Title = name, Subtitle = role, Image = photo.'],
            'technologies' => ['Technologies', 'Title = name, Subtitle = group (Frontend, Backend, ...), Value = logo key.'],
            'project_categories' => ['Portfolio filter categories', 'Title = category name, matching the project category.'],
            'blog_categories' => ['Blog filter categories', 'Title = category name, matching the article tag.'],
            'case_study_metrics' => ['Case studies: headline metrics', 'Title = label, Value = e.g. "200%+".'],
            'contact_service_options' => ['Contact form: service options', 'Title = option.'],
            'contact_budget_options' => ['Contact form: budget options', 'Title = option.'],
        ],

        'pages' => [
            'home' => 'Home',
            'about' => 'About Us',
            'services' => 'Services',
            'portfolio' => 'Portfolio',
            'case-studies' => 'Case Studies',
            'blog' => 'Blog',
            'contact' => 'Contact',
            'website-development' => 'Service: Website Development',
            'software-development' => 'Service: Software Development',
            'mobile-app-development' => 'Service: Mobile App Development',
            'custom-software' => 'Service: Custom Software',
            'ecommerce-solutions' => 'Service: E-Commerce Solutions',
            'digital-marketing' => 'Service: Digital Marketing',
            'seo' => 'Service: SEO',
            'social-media-marketing' => 'Service: Social Media Marketing',
            'paid-advertising' => 'Service: Paid Advertising',
        ],

        // Pages rendered by the service detail template (they have `content` blocks).
        'service_pages' => [
            'website-development', 'software-development', 'mobile-app-development', 'custom-software',
            'ecommerce-solutions', 'digital-marketing', 'seo', 'social-media-marketing', 'paid-advertising',
        ],

        'service_categories' => [
            'IT Services' => 'IT Services',
            'Digital Marketing' => 'Digital Marketing',
        ],

        // Must match `icons` in roots_it/src/utils/iconMap.jsx.
        'icons' => [
            'activity', 'award', 'cart', 'chart', 'check', 'clipboard', 'clock', 'cloud', 'code', 'compass',
            'cpu', 'database', 'edit', 'eye', 'feather', 'globe', 'grid', 'heart', 'layers', 'layout', 'lock',
            'mail', 'monitor', 'package', 'pen', 'phone', 'pin', 'refresh', 'search', 'server', 'settings',
            'share', 'shield', 'smartphone', 'support', 'target', 'thumbsUp', 'trending', 'users', 'zap',
        ],

        // Must match `techIcons` in roots_it/src/utils/iconMap.jsx.
        'tech_icons' => [
            'android', 'aws', 'css', 'express', 'firebase', 'flutter', 'html', 'ios', 'javascript', 'mongodb',
            'mysql', 'nextjs', 'node', 'php', 'postgresql', 'python', 'react', 'react-native', 'typescript', 'vercel',
        ],

    ],

];
