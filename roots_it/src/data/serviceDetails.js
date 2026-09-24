/**
 * Rich content for the 9 individual service pages.
 * `ServiceDetailTemplate` renders any entry by slug, so each page component
 * is a one-liner. Swap this object for `GET /services/:slug` later.
 */

const detail = {
  /* ---------------------------------------------------------------- */
  'website-development': {
    slug: 'website-development',
    category: 'IT Services',
    parent: { label: 'Services', to: '/services' },
    seoTitle: 'Website Development Services | Roots Technology',
    seoDescription:
      'Custom website development with React and Next.js — business sites, corporate sites, portfolios, landing pages, CMS and fully custom builds.',
    eyebrow: 'Website Development',
    title: 'Websites engineered to convert, rank and scale',
    subtitle:
      'We design and build fast, accessible, SEO-ready websites — from a single high-converting landing page to a multi-language corporate platform.',
    intro: [
      'Your website is the hub of every marketing channel you run. We treat it like a product: measurable, maintainable and built on a modern stack that your team (or ours) can extend for years.',
      'Every build ships with clean semantic markup, Core Web Vitals in the green, a component library, and a content model that is ready to connect to a CMS whenever you want to edit pages yourself.',
    ],
    offerings: [
      { title: 'Business Websites', description: 'Compact, persuasive sites that turn visitors into qualified enquiries.', icon: 'globe' },
      { title: 'Corporate Websites', description: 'Multi-section, multi-language platforms with investor, careers and press areas.', icon: 'layout' },
      { title: 'Portfolio Websites', description: 'Visual, case-study-driven sites for studios, agencies and individuals.', icon: 'grid' },
      { title: 'Landing Pages', description: 'Single-goal pages wired to your ad campaigns and analytics for fast iteration.', icon: 'target' },
      { title: 'CMS Websites', description: 'Headless CMS integration so non-technical teams publish without deploys.', icon: 'edit' },
      { title: 'Custom Websites', description: 'Bespoke interactions, animations and integrations when templates fall short.', icon: 'code' },
    ],
    technologies: ['react', 'nextjs', 'javascript', 'typescript', 'html', 'css'],
    process: [
      { title: 'Discovery', description: 'Goals, audiences, competitors and success metrics.' },
      { title: 'Wireframes', description: 'Low-fidelity structure and content hierarchy for every page.' },
      { title: 'UI Design', description: 'A polished design system in Figma, reviewed with you.' },
      { title: 'Development', description: 'Responsive, componentised front-end with CMS wiring.' },
      { title: 'QA & SEO', description: 'Cross-device testing, accessibility and technical SEO pass.' },
      { title: 'Launch & Support', description: 'Deployment, analytics handover and a care plan.' },
    ],
    benefits: [
      'Sub-2-second load times and green Core Web Vitals',
      'Accessible to WCAG 2.1 AA standards',
      'Editable in a CMS — no developer needed for copy changes',
      'Component library for consistent future pages',
      'Analytics, event tracking and lead capture from day one',
      'Hosting, monitoring and security handled by us',
    ],
    faqs: [
      { q: 'How long does a website take?', a: 'A focused marketing site is typically 3–5 weeks. Larger corporate platforms with a CMS and multiple languages run 8–12 weeks.' },
      { q: 'Can we edit the site ourselves afterwards?', a: 'Yes. We integrate a headless CMS (Sanity, Contentful or Strapi) so your team can edit pages, blog posts and components without touching code.' },
      { q: 'Do you provide the design too?', a: 'Yes — UI/UX design is part of every engagement unless you already have finished designs you want us to build.' },
      { q: 'What about hosting?', a: 'We deploy to Vercel or AWS and can manage hosting, SSL, backups and monitoring on a monthly care plan.' },
    ],
    asideTitle: 'Included in every build',
    asidePoints: ['Responsive 320px → 4K', 'Technical SEO setup', 'Analytics & event tracking', 'CMS-ready content model', '30 days post-launch support'],
    cta: { title: 'Ready to start your website project?' },
  },

  /* ---------------------------------------------------------------- */
  'software-development': {
    slug: 'software-development',
    category: 'IT Services',
    parent: { label: 'Services', to: '/services' },
    seoTitle: 'Software Development Services | Roots Technology',
    seoDescription:
      'Custom software, SaaS, CRM and ERP development. Enterprise-grade web platforms and business automation built to scale.',
    eyebrow: 'Software Development',
    title: 'Software that removes friction from how you work',
    subtitle:
      'We build custom platforms, SaaS products and internal tools — architected for security, uptime and the load you expect three years from now.',
    intro: [
      'Off-the-shelf tools force your processes to bend around their assumptions. Custom software does the opposite: it encodes your workflow, your data model and your rules exactly.',
      'Our teams work in short iterations with continuous delivery, automated testing and observability baked in, so you see working software early and often.',
    ],
    offerings: [
      { title: 'Custom Software Development', description: 'Purpose-built applications mapped to your exact operations.', icon: 'code' },
      { title: 'Enterprise Software', description: 'Role-based access, audit trails and integrations for large organisations.', icon: 'server' },
      { title: 'SaaS Development', description: 'Multi-tenant products with billing, onboarding and usage analytics.', icon: 'cloud' },
      { title: 'CRM Development', description: 'Sales pipelines, contact intelligence and reporting tailored to your team.', icon: 'users' },
      { title: 'ERP Solutions', description: 'Inventory, finance, HR and operations unified in one system.', icon: 'grid' },
      { title: 'Business Automation', description: 'Replace manual, error-prone steps with reliable automated workflows.', icon: 'refresh' },
    ],
    technologies: ['react', 'nextjs', 'typescript', 'node', 'express', 'python', 'postgresql', 'mongodb', 'aws'],
    process: [
      { title: 'Discovery & Scoping', description: 'Workshops to map processes, data and integration points.' },
      { title: 'Architecture', description: 'System design, data model, security model and delivery plan.' },
      { title: 'Prototype', description: 'A clickable prototype of the core workflow for validation.' },
      { title: 'Iterative Build', description: 'Two-week sprints with demos, automated tests and CI/CD.' },
      { title: 'Hardening', description: 'Load testing, penetration testing and observability.' },
      { title: 'Rollout & Support', description: 'Phased launch, training and an SLA-backed support plan.' },
    ],
    benefits: [
      'Architecture designed for 10x your current usage',
      'Automated test suites and CI/CD from sprint one',
      'Security reviews and role-based access control',
      'Documentation and knowledge transfer to your team',
      'Cloud infrastructure as code — reproducible environments',
      'Ongoing SLA support and enhancement roadmap',
    ],
    industries: ['Fintech', 'Healthcare', 'Logistics', 'Manufacturing', 'Education', 'Real Estate', 'Retail', 'Professional Services'],
    faqs: [
      { q: 'Do you work with our existing systems?', a: 'Yes. Most projects involve integrating with ERPs, payment providers, data warehouses or legacy databases via APIs or scheduled syncs.' },
      { q: 'Who owns the code?', a: 'You do. All source code, infrastructure definitions and documentation are handed over and hosted in your accounts.' },
      { q: 'Can you take over an existing codebase?', a: 'Yes — we start with a technical audit, then stabilise and extend. We are transparent if a rewrite is genuinely the better option.' },
      { q: 'How do you price projects?', a: 'Fixed-price for well-defined scopes, or a dedicated team model (monthly) for evolving products.' },
    ],
    asideTitle: 'Engagement models',
    asidePoints: ['Fixed-scope project', 'Dedicated development team', 'Staff augmentation', 'Technical audit & rescue', 'Maintenance & SLA'],
    cta: { title: 'Let’s scope your software project' },
  },

  /* ---------------------------------------------------------------- */
  'mobile-app-development': {
    slug: 'mobile-app-development',
    category: 'IT Services',
    parent: { label: 'Services', to: '/services' },
    seoTitle: 'Mobile App Development Services | Roots Technology',
    seoDescription:
      'Android, iOS and React Native app development — UI/UX design, cross-platform builds, store launch and maintenance.',
    eyebrow: 'Mobile App Development',
    title: 'Mobile apps people keep on their home screen',
    subtitle:
      'From concept and UI/UX to store launch and iteration — native and cross-platform apps built for performance and retention.',
    intro: [
      'A great app feels instant, works offline, respects the platform’s conventions and earns its place through genuine utility. That is the bar we build to.',
      'We ship with crash reporting, analytics and feature flags so you can learn from real usage and release improvements safely.',
    ],
    offerings: [
      { title: 'Android App Development', description: 'Native Kotlin apps that follow Material guidelines and run smoothly on low-end devices.', icon: 'smartphone' },
      { title: 'iOS App Development', description: 'Native Swift apps built to Apple’s Human Interface Guidelines.', icon: 'smartphone' },
      { title: 'Cross-Platform Development', description: 'One React Native codebase, near-native feel, faster time to market.', icon: 'layers' },
      { title: 'React Native Development', description: 'Our specialty — shared logic, native modules where they matter.', icon: 'code' },
      { title: 'App UI/UX Design', description: 'Flows, prototypes and design systems tuned for thumb-reach and clarity.', icon: 'pen' },
      { title: 'App Maintenance', description: 'OS updates, library upgrades, monitoring and feature iteration.', icon: 'refresh' },
    ],
    technologies: ['react-native', 'android', 'ios', 'firebase', 'typescript', 'node'],
    process: [
      { title: 'Product Definition', description: 'Personas, core loop, feature list and success metrics.' },
      { title: 'UX & Prototype', description: 'Interactive prototype tested with real users.' },
      { title: 'Architecture', description: 'Offline strategy, state management, API contracts.' },
      { title: 'Build & Test', description: 'Sprints with device-lab testing and beta builds.' },
      { title: 'Store Launch', description: 'App Store and Play Store submission, assets and review support.' },
      { title: 'Iterate', description: 'Analytics-driven improvements and staged rollouts.' },
    ],
    benefits: [
      'Single codebase for iOS and Android with React Native',
      '60 fps interactions and fast cold-start times',
      'Offline-first data layer where it matters',
      'Crash reporting, analytics and feature flags built in',
      'App Store / Play Store submission handled end to end',
      'Post-launch iteration and OS-update maintenance',
    ],
    industries: ['On-demand services', 'Fintech', 'Health & fitness', 'Retail & loyalty', 'Logistics', 'Media'],
    faqs: [
      { q: 'Native or cross-platform?', a: 'For most products React Native delivers the best value. We recommend fully native when you need heavy device-level performance (AR, complex graphics) or deep OS integration.' },
      { q: 'Do you handle the store submissions?', a: 'Yes, including store listings, screenshots, privacy declarations and responding to review feedback.' },
      { q: 'Can you build the backend too?', a: 'Yes — we build the API, auth, push notifications and admin dashboard as part of the same engagement.' },
      { q: 'What about ongoing maintenance?', a: 'We offer monthly plans covering OS updates, dependency upgrades, monitoring and a block of iteration hours.' },
    ],
    asideTitle: 'What you get',
    asidePoints: ['iOS + Android builds', 'Backend API & admin panel', 'Push notifications', 'Analytics & crash reporting', 'Store launch support'],
    cta: { title: 'Have an app idea? Let’s talk' },
  },

  /* ---------------------------------------------------------------- */
  'custom-software': {
    slug: 'custom-software',
    category: 'IT Services',
    parent: { label: 'Services', to: '/services' },
    seoTitle: 'Custom Software Solutions | Roots Technology',
    seoDescription:
      'Business automation, CRM and ERP systems, custom dashboards, workflow automation and API / third-party integrations.',
    eyebrow: 'Custom Software Solutions',
    title: 'Solve the problem no product on the market solves',
    subtitle:
      'When spreadsheets, disconnected tools and manual handoffs are holding you back, we build the system that fits your business exactly.',
    intro: [
      'Every growing company reaches a point where its operations outgrow generic tools. Data lives in five places, reports are assembled by hand, and onboarding a new hire takes weeks.',
      'We map those processes, find the highest-leverage automation, and deliver a system your team actually wants to use.',
    ],
    offerings: [
      { title: 'Business Automation', description: 'Automate approvals, notifications, data entry and recurring tasks.', icon: 'refresh' },
      { title: 'CRM Systems', description: 'A customer database and pipeline shaped around how you actually sell.', icon: 'users' },
      { title: 'ERP Systems', description: 'Connect inventory, orders, finance and operations in one source of truth.', icon: 'grid' },
      { title: 'Custom Dashboards', description: 'Live KPIs pulled from every system into one clear view.', icon: 'chart' },
      { title: 'Workflow Automation', description: 'Model multi-step processes with rules, roles and audit trails.', icon: 'activity' },
      { title: 'API & Third-Party Integration', description: 'Make your tools talk to each other — payments, ERPs, shipping, data.', icon: 'layers' },
    ],
    technologies: ['react', 'typescript', 'node', 'python', 'postgresql', 'mongodb', 'aws', 'firebase'],
    process: [
      { title: 'Process Mapping', description: 'Shadow your team, document the as-is workflow and pain points.' },
      { title: 'Opportunity Sizing', description: 'Rank automations by time saved and error reduction.' },
      { title: 'Solution Design', description: 'Data model, integrations, roles and a phased delivery plan.' },
      { title: 'Build', description: 'Ship the highest-value module first, then iterate.' },
      { title: 'Adoption', description: 'Training, documentation and change management support.' },
      { title: 'Optimise', description: 'Measure impact and expand to the next process.' },
    ],
    benefits: [
      'Hours of manual work removed every week',
      'One source of truth instead of scattered spreadsheets',
      'Fewer errors thanks to validation and automation',
      'Faster onboarding with guided workflows',
      'Real-time visibility for management',
      'A platform that grows with new modules over time',
    ],
    industries: ['Distribution & wholesale', 'Field services', 'Healthcare admin', 'Construction', 'Agencies', 'Non-profits'],
    faqs: [
      { q: 'We already use tools like HubSpot and QuickBooks — do we replace them?', a: 'Not necessarily. Often the right answer is to integrate them and build only the missing glue and workflows on top.' },
      { q: 'How do you keep the project from ballooning?', a: 'We deliver in modules. The first release targets one painful process and proves value before we expand.' },
      { q: 'Will our team actually use it?', a: 'Adoption is part of scope — we involve end users in design, keep the UI simple, and run training sessions at rollout.' },
      { q: 'Can it run on our servers?', a: 'Yes. We deploy to your cloud account or on-premise infrastructure depending on your compliance needs.' },
    ],
    asideTitle: 'Common starting points',
    asidePoints: ['Replace a fragile spreadsheet', 'Automate a manual approval chain', 'Unify two disconnected systems', 'Build a management dashboard', 'Add a customer / partner portal'],
    cta: { title: 'Tell us what’s slowing your team down' },
  },

  /* ---------------------------------------------------------------- */
  'ecommerce-solutions': {
    slug: 'ecommerce-solutions',
    category: 'IT Services',
    parent: { label: 'Services', to: '/services' },
    seoTitle: 'E-Commerce Development Solutions | Roots Technology',
    seoDescription:
      'Custom e-commerce websites, Shopify and WooCommerce development, payment gateway integration, inventory management and e-commerce mobile apps.',
    eyebrow: 'E-Commerce Solutions',
    title: 'Storefronts built to sell, not just to display',
    subtitle:
      'Fast, secure and merchandising-friendly online stores on Shopify, WooCommerce or a fully custom stack — with the operations tooling to match.',
    intro: [
      'A store that loads slowly or checks out clumsily leaks revenue on every visit. We obsess over speed, trust signals and a checkout that gets out of the way.',
      'Behind the storefront, we wire up inventory, orders, fulfilment and analytics so running the shop does not become a full-time data-entry job.',
    ],
    offerings: [
      { title: 'Custom E-Commerce Websites', description: 'Headless storefronts with bespoke merchandising and content.', icon: 'code' },
      { title: 'Shopify Development', description: 'Custom themes, apps and Shopify Plus builds.', icon: 'cart' },
      { title: 'WooCommerce Development', description: 'WordPress + WooCommerce stores with tailored extensions.', icon: 'settings' },
      { title: 'Payment Gateway Integration', description: 'Stripe, PayPal, Apple Pay, local gateways and fraud protection.', icon: 'lock' },
      { title: 'Inventory Management', description: 'Real-time stock, variants, suppliers and low-stock alerts.', icon: 'package' },
      { title: 'E-Commerce Mobile Apps', description: 'Companion shopping apps with push, wishlists and fast reorder.', icon: 'smartphone' },
    ],
    technologies: ['react', 'nextjs', 'typescript', 'node', 'php', 'mysql', 'postgresql', 'aws'],
    process: [
      { title: 'Catalogue & UX Audit', description: 'Product data, categories, journeys and drop-off points.' },
      { title: 'Design', description: 'Storefront, PDP and a checkout optimised for conversion.' },
      { title: 'Build', description: 'Storefront, CMS, payments and integrations.' },
      { title: 'Operations Setup', description: 'Inventory, shipping, tax and order workflows.' },
      { title: 'QA & Load Test', description: 'Payment edge cases, performance and security checks.' },
      { title: 'Launch & Grow', description: 'Migration, analytics and a CRO backlog.' },
    ],
    benefits: [
      'PCI-compliant, secure payments with multiple methods',
      'Structured product management with variants and bundles',
      'Order and fulfilment workflows that scale with volume',
      'Customer accounts, wishlists and reorder flows',
      'Analytics: funnel, cohort and product performance',
      'Architecture that handles seasonal traffic spikes',
    ],
    industries: ['Fashion & apparel', 'Beauty & wellness', 'Food & beverage', 'Home & lifestyle', 'Electronics', 'B2B wholesale'],
    faqs: [
      { q: 'Shopify or custom?', a: 'Shopify covers most needs brilliantly and we recommend it by default. We go custom / headless when you need unusual merchandising, complex B2B pricing or a content-heavy experience.' },
      { q: 'Can you migrate our existing store?', a: 'Yes — products, customers, orders and URL redirects are all part of a migration plan so SEO and history are preserved.' },
      { q: 'Do you handle integrations with our ERP / 3PL?', a: 'Yes. Inventory, order and fulfilment sync with systems like NetSuite, ShipStation or custom warehouses is a common part of scope.' },
      { q: 'Will the store be fast?', a: 'Performance is a KPI on every build — image optimisation, edge caching and lean JavaScript keep pages quick even with large catalogues.' },
    ],
    asideTitle: 'Store operations we set up',
    asidePoints: ['Payments & fraud protection', 'Inventory & variants', 'Shipping & tax rules', 'Order & returns workflow', 'Analytics & CRO backlog'],
    cta: { title: 'Let’s build a store that converts' },
  },

  /* ---------------------------------------------------------------- */
  'digital-marketing': {
    slug: 'digital-marketing',
    category: 'Digital Marketing',
    parent: { label: 'Digital Marketing', to: '/digital-marketing' },
    seoTitle: 'Digital Marketing Services | Roots Technology',
    seoDescription:
      'Full-funnel digital marketing — SEO, paid ads, social media, content, email, branding and conversion rate optimisation with transparent reporting.',
    eyebrow: 'Digital Marketing',
    title: 'Growth marketing with engineering discipline',
    subtitle:
      'One team across SEO, paid, social, content and CRO — aligned to pipeline and revenue, not vanity metrics.',
    intro: [
      'Most agencies optimise the channel they were hired for. We start from your funnel: where demand comes from, where it stalls, and which lever moves revenue fastest right now.',
      'You get a clear strategy, a shared dashboard, and monthly reviews where we talk about outcomes — leads, CAC, LTV — not just impressions.',
    ],
    offerings: [
      { title: 'SEO', description: 'Technical, content and authority work for compounding organic growth.', icon: 'search' },
      { title: 'Local SEO', description: 'Map-pack visibility, reviews and location pages.', icon: 'pin' },
      { title: 'Google Ads', description: 'Search, Shopping, Display and YouTube tuned to CPA targets.', icon: 'target' },
      { title: 'Meta Ads', description: 'Full-funnel Facebook and Instagram with creative testing.', icon: 'trending' },
      { title: 'Social Media Marketing', description: 'Organic strategy, content and community management.', icon: 'share' },
      { title: 'Content Marketing', description: 'Editorial planning, production and distribution.', icon: 'edit' },
      { title: 'Email Marketing', description: 'Lifecycle automation, newsletters and segmentation.', icon: 'mail' },
      { title: 'Branding', description: 'Positioning, identity and messaging systems.', icon: 'pen' },
      { title: 'Conversion Rate Optimization', description: 'Research, testing and UX fixes to lift conversion.', icon: 'chart' },
    ],
    process: [
      { title: 'Audit', description: 'Analytics, channels, competitors and funnel diagnostics.' },
      { title: 'Strategy', description: 'Channel mix, targets, budget and a 90-day roadmap.' },
      { title: 'Setup', description: 'Tracking, dashboards, creative and campaign infrastructure.' },
      { title: 'Execute', description: 'Ship campaigns and content on a predictable cadence.' },
      { title: 'Analyse', description: 'Weekly optimisation, monthly business reviews.' },
      { title: 'Scale', description: 'Double down on what works, cut what does not.' },
    ],
    benefits: [
      'A single strategy across every channel',
      'Shared live dashboard — no black boxes',
      'Reporting tied to leads, CAC and revenue',
      'Senior strategists on your account, not juniors',
      'Creative, content and media under one roof',
      'Month-to-month after the first 90 days',
    ],
    faqs: [
      { q: 'What is the minimum engagement?', a: 'We ask for an initial 90-day period so strategy and tracking have time to produce signal. After that it is month-to-month.' },
      { q: 'Do you require a media budget minimum?', a: 'For paid channels we typically recommend at least $3,000/month in media per platform to gather meaningful data.' },
      { q: 'Who owns the accounts and data?', a: 'You do. All ad accounts, analytics and dashboards are in your ownership from day one.' },
      { q: 'How do you report?', a: 'A live dashboard you can check any time, plus a monthly review call focused on business outcomes and next steps.' },
    ],
    asideTitle: 'How we report',
    asidePoints: ['Live Looker Studio dashboard', 'Weekly optimisation notes', 'Monthly business review call', 'Quarterly strategy reset', 'Full account ownership'],
    cta: { title: 'Get a free marketing audit' },
  },

  /* ---------------------------------------------------------------- */
  seo: {
    slug: 'seo',
    category: 'Digital Marketing',
    parent: { label: 'Digital Marketing', to: '/digital-marketing' },
    seoTitle: 'SEO Services | Roots Technology',
    seoDescription:
      'On-page, off-page and technical SEO, local SEO, keyword research, competitor analysis, SEO audits and link building with monthly reporting.',
    eyebrow: 'Search Engine Optimization',
    title: 'Organic traffic that compounds every month',
    subtitle:
      'Technical foundations, content that answers real queries, and authority building — measured against rankings, traffic and pipeline.',
    intro: [
      'SEO is not a one-time project. It is a system: fix what search engines struggle to crawl, publish content that matches intent, and earn links that build trust.',
      'We prioritise ruthlessly by opportunity and effort, so early wins fund the longer plays.',
    ],
    offerings: [
      { title: 'On-Page SEO', description: 'Titles, structure, internal links and content optimisation.', icon: 'edit' },
      { title: 'Off-Page SEO', description: 'Digital PR and link acquisition that moves authority metrics.', icon: 'share' },
      { title: 'Technical SEO', description: 'Crawlability, indexation, speed, schema and Core Web Vitals.', icon: 'settings' },
      { title: 'Local SEO', description: 'Google Business Profile, citations and location landing pages.', icon: 'pin' },
      { title: 'Keyword Research', description: 'Intent-mapped keyword universe and content plan.', icon: 'search' },
      { title: 'Competitor Analysis', description: 'Gap analysis on content, keywords and backlinks.', icon: 'eye' },
      { title: 'SEO Audit', description: 'A prioritised, plain-English action list.', icon: 'clipboard' },
      { title: 'Link Building', description: 'Editorially earned links from relevant, credible sites.', icon: 'layers' },
    ],
    process: [
      { title: 'Website Audit', description: 'Technical, content and authority baseline.' },
      { title: 'Keyword Research', description: 'Map queries to intent and to funnel stage.' },
      { title: 'Competitor Analysis', description: 'Find winnable gaps and quick wins.' },
      { title: 'On-Page Optimisation', description: 'Templates, metadata, structure and internal links.' },
      { title: 'Content Optimisation', description: 'Improve existing pages, plan new ones.' },
      { title: 'Technical SEO', description: 'Fix crawl, speed and schema issues.' },
      { title: 'Link Building', description: 'Outreach and digital PR campaigns.' },
      { title: 'Reporting', description: 'Monthly rankings, traffic and conversion review.' },
    ],
    benefits: [
      'A prioritised roadmap, not a 200-page audit you ignore',
      'Content briefs your writers (or ours) can execute',
      'Technical fixes implemented, not just recommended',
      'White-hat link building — no risky shortcuts',
      'Rank tracking plus traffic and conversion reporting',
      'Clear attribution to leads and revenue where possible',
    ],
    faqs: [
      { q: 'How long until we see results?', a: 'Technical and on-page wins can show within 4–8 weeks. Competitive content and authority gains typically take 4–6 months to mature.' },
      { q: 'Do you guarantee #1 rankings?', a: 'No credible SEO does. We commit to a defined scope of work and report transparently on rankings, traffic and conversions.' },
      { q: 'Can you implement the fixes or just advise?', a: 'We can do both. Many clients have us implement directly; others want us to brief their dev team. Your call.' },
      { q: 'Is link building safe?', a: 'Yes — we only pursue editorially earned links from relevant sites. We never buy links from networks or use private blog networks.' },
    ],
    asideTitle: 'Deliverables each month',
    asidePoints: ['Prioritised task backlog', 'Content briefs & optimisation', 'Technical fix implementation', 'Link acquisition report', 'Rankings & traffic dashboard'],
    cta: { title: 'Request a free SEO audit' },
  },

  /* ---------------------------------------------------------------- */
  'social-media-marketing': {
    slug: 'social-media-marketing',
    category: 'Digital Marketing',
    parent: { label: 'Digital Marketing', to: '/digital-marketing' },
    seoTitle: 'Social Media Marketing Services | Roots Technology',
    seoDescription:
      'Social media strategy, content creation, post and reel design, community management and paid social across Instagram, Facebook, LinkedIn, X and YouTube.',
    eyebrow: 'Social Media Marketing',
    title: 'A social presence that builds an audience, not just a feed',
    subtitle:
      'Platform-native strategy, a reliable content engine and real community management across the channels where your customers actually are.',
    intro: [
      'Posting consistently is table stakes. Growth comes from a clear point of view, formats that suit each platform, and paying attention to what the audience responds to.',
      'We run a monthly content system — plan, produce, publish, engage, review — so the channel keeps compounding instead of going quiet after week three.',
    ],
    offerings: [
      { title: 'Social Media Strategy', description: 'Positioning, pillars, cadence and channel priorities.', icon: 'compass' },
      { title: 'Content Creation', description: 'Copy, graphics and short-form video on a monthly calendar.', icon: 'edit' },
      { title: 'Post Design', description: 'On-brand templates and carousels that stop the scroll.', icon: 'pen' },
      { title: 'Reels Strategy', description: 'Hooks, formats and a shot list for repeatable short video.', icon: 'activity' },
      { title: 'Community Management', description: 'Replies, DMs and proactive engagement in your voice.', icon: 'users' },
      { title: 'Paid Advertising', description: 'Boosting and full campaigns to amplify the best content.', icon: 'target' },
    ],
    process: [
      { title: 'Audit & Strategy', description: 'Benchmark channels, define pillars and goals.' },
      { title: 'Content System', description: 'Templates, tone guide and a monthly calendar.' },
      { title: 'Production', description: 'Batch-produce posts, carousels and reels.' },
      { title: 'Publishing', description: 'Schedule, optimise posting times and hashtags.' },
      { title: 'Engagement', description: 'Daily community management and social listening.' },
      { title: 'Review', description: 'Monthly report and next-month plan.' },
    ],
    benefits: [
      'A documented strategy, not ad-hoc posting',
      'Consistent, on-brand content every week',
      'Short-form video system your team can sustain',
      'Active community management in your voice',
      'Paid amplification of proven organic winners',
      'Monthly reporting on growth, engagement and traffic',
    ],
    stats: [
      { value: 3, suffix: 'x', label: 'Avg. engagement lift in 90 days' },
      { value: 40, suffix: '+', label: 'Content assets produced monthly' },
      { value: 5, suffix: '', label: 'Platforms supported' },
      { value: 24, suffix: 'h', label: 'Community response SLA' },
    ],
    faqs: [
      { q: 'Which platforms should we be on?', a: 'We recommend focusing on two or three where your audience is active rather than spreading thin. For B2B that is often LinkedIn + one visual channel; for B2C, Instagram + TikTok/Reels.' },
      { q: 'Do you create the video content?', a: 'Yes — we handle scripting, editing and motion graphics. For on-camera footage we either direct your team remotely or arrange a shoot.' },
      { q: 'Can you manage paid and organic together?', a: 'Yes, and we recommend it — organic tells us which content deserves budget behind it.' },
      { q: 'How much input do we need to provide?', a: 'About one hour a month for a planning call plus quick approvals. We handle the rest.' },
    ],
    asideTitle: 'Monthly package includes',
    asidePoints: ['Content calendar', '16–20 feed posts', '8–12 reels / shorts', 'Daily community management', 'Performance report'],
    cta: { title: 'Grow your social channels with us' },
  },

  /* ---------------------------------------------------------------- */
  'paid-advertising': {
    slug: 'paid-advertising',
    category: 'Digital Marketing',
    parent: { label: 'Digital Marketing', to: '/digital-marketing' },
    seoTitle: 'Paid Advertising & PPC Services | Roots Technology',
    seoDescription:
      'Google Ads, Meta Ads, Instagram, LinkedIn and YouTube advertising plus remarketing — strategy, targeting, creative, optimisation and reporting.',
    eyebrow: 'Paid Advertising',
    title: 'Paid media managed to a number that matters',
    subtitle:
      'Every campaign is built around a target CPA or ROAS, with creative testing and tight feedback loops to get there faster.',
    intro: [
      'Paid channels reward discipline: clean tracking, a structured account, strong creative and the patience to let tests reach significance.',
      'We treat your budget like our own — pausing what is not working quickly and scaling what is, with full visibility for you the whole way.',
    ],
    offerings: [
      { title: 'Google Ads', description: 'Search, Performance Max, Shopping and Display.', icon: 'search' },
      { title: 'Meta Ads', description: 'Facebook and Instagram full-funnel campaigns.', icon: 'share' },
      { title: 'Instagram Ads', description: 'Feed, Stories and Reels placements with native creative.', icon: 'trending' },
      { title: 'LinkedIn Ads', description: 'ABM and lead-gen for considered B2B purchases.', icon: 'users' },
      { title: 'YouTube Ads', description: 'In-stream and Shorts campaigns for reach and retargeting.', icon: 'activity' },
      { title: 'Remarketing', description: 'Win back visitors and nurture leads across the web.', icon: 'refresh' },
    ],
    process: [
      { title: 'Campaign Strategy', description: 'Goals, funnel map, budget split and KPI targets.' },
      { title: 'Audience Targeting', description: 'Segments, lookalikes, intent and exclusion lists.' },
      { title: 'Ad Creatives', description: 'Concepts, variations and a testing matrix.' },
      { title: 'Launch', description: 'Structured account build with conversion tracking.' },
      { title: 'Optimisation', description: 'Bid, budget, creative and landing-page iteration.' },
      { title: 'Reporting', description: 'Weekly notes and a monthly review against targets.' },
    ],
    benefits: [
      'Campaigns managed to CPA / ROAS targets',
      'Server-side conversion tracking set up properly',
      'Systematic creative testing, not guesswork',
      'Landing-page and offer feedback, not just ad tweaks',
      'Transparent reporting — you own every account',
      'No long lock-in after the initial 90 days',
    ],
    faqs: [
      { q: 'What budget do we need?', a: 'As a guide, at least $3,000/month in media per platform gives campaigns enough data to optimise within a reasonable timeframe.' },
      { q: 'Do you build the landing pages?', a: 'We can. Where we do not, we give specific, prioritised recommendations — the landing page usually matters more than the ad.' },
      { q: 'How is your fee structured?', a: 'A flat monthly management fee based on scope and number of platforms — not a percentage of spend, so our incentives stay aligned.' },
      { q: 'How soon will we see results?', a: 'Early signal in 2–4 weeks; stable, optimised performance usually by month two once tracking and tests have matured.' },
    ],
    asideTitle: 'Every engagement includes',
    asidePoints: ['Conversion tracking audit', 'Account restructure', 'Creative testing plan', 'Weekly optimisation', 'Monthly review vs targets'],
    cta: { title: 'Get a free paid-media audit' },
  },
};

export function getServiceDetail(slug) {
  return detail[slug] || null;
}

export const serviceSlugs = Object.keys(detail);

export default detail;
