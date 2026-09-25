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

];
