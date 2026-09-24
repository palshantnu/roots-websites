/**
 * Service catalogue used on Home, Services page and Footer.
 * `link` points to a detail page where one exists (thin pages backed by
 * `serviceDetails.js`); others are anchored on the Services page.
 *
 * Shape mirrors a typical CMS "services" collection:
 *   { id, title, slug, category, description, icon, image, link }
 */

export const itServices = [
  {
    id: 1,
    title: 'Website Development',
    slug: 'website-development',
    category: 'IT Services',
    description:
      'Fast, responsive, SEO-ready marketing sites and web apps built with React and Next.js.',
    icon: 'monitor',
    link: '/services/website-development',
  },
  {
    id: 2,
    title: 'Software Development',
    slug: 'software-development',
    category: 'IT Services',
    description:
      'Custom web platforms, SaaS products and internal tools engineered for scale and reliability.',
    icon: 'code',
    link: '/services/software-development',
  },
  {
    id: 3,
    title: 'App Development',
    slug: 'app-development',
    category: 'IT Services',
    description:
      'Cross-platform and native mobile apps with polished UX and rock-solid performance.',
    icon: 'smartphone',
    link: '/services/mobile-app-development',
  },
  {
    id: 4,
    title: 'Custom Software Solutions',
    slug: 'custom-software',
    category: 'IT Services',
    description:
      'CRM, ERP, dashboards and workflow automation tailored precisely to how your business runs.',
    icon: 'settings',
    link: '/services/custom-software',
  },
  {
    id: 5,
    title: 'E-Commerce Solutions',
    slug: 'ecommerce-solutions',
    category: 'IT Services',
    description:
      'Conversion-focused storefronts on Shopify, WooCommerce or fully custom stacks.',
    icon: 'cart',
    link: '/services/ecommerce-solutions',
  },
  {
    id: 6,
    title: 'Mobile App Development',
    slug: 'mobile-app-development',
    category: 'IT Services',
    description:
      'Android, iOS and React Native builds — from UI/UX design through store launch and maintenance.',
    icon: 'layers',
    link: '/services/mobile-app-development',
  },
];

export const marketingServices = [
  {
    id: 7,
    title: 'Search Engine Optimization',
    slug: 'seo',
    category: 'Digital Marketing',
    description:
      'Technical, on-page and off-page SEO that compounds into durable organic traffic.',
    icon: 'search',
    link: '/digital-marketing/seo',
  },
  {
    id: 8,
    title: 'Local SEO',
    slug: 'local-seo',
    category: 'Digital Marketing',
    description:
      'Google Business Profile, citations and local content to own the map pack in your area.',
    icon: 'pin',
    link: '/digital-marketing/seo',
  },
  {
    id: 9,
    title: 'Social Media Marketing',
    slug: 'social-media-marketing',
    category: 'Digital Marketing',
    description:
      'Strategy, content and community management across Instagram, Facebook, LinkedIn and more.',
    icon: 'share',
    link: '/digital-marketing/social-media-marketing',
  },
  {
    id: 10,
    title: 'Google Ads',
    slug: 'google-ads',
    category: 'Digital Marketing',
    description:
      'Search, Display, Shopping and YouTube campaigns tuned for cost-efficient pipeline.',
    icon: 'target',
    link: '/digital-marketing/paid-advertising',
  },
  {
    id: 11,
    title: 'Meta Ads',
    slug: 'meta-ads',
    category: 'Digital Marketing',
    description:
      'Full-funnel Facebook and Instagram advertising with creative testing and retargeting.',
    icon: 'trending',
    link: '/digital-marketing/paid-advertising',
  },
  {
    id: 12,
    title: 'Content Marketing',
    slug: 'content-marketing',
    category: 'Digital Marketing',
    description:
      'Editorial strategy, articles and lead magnets that attract and convert your audience.',
    icon: 'edit',
    link: '/digital-marketing',
  },
  {
    id: 13,
    title: 'Email Marketing',
    slug: 'email-marketing',
    category: 'Digital Marketing',
    description:
      'Lifecycle flows, newsletters and automation that turn subscribers into revenue.',
    icon: 'mail',
    link: '/digital-marketing',
  },
  {
    id: 14,
    title: 'Branding',
    slug: 'branding',
    category: 'Digital Marketing',
    description:
      'Visual identity, messaging and guidelines that make your company instantly recognisable.',
    icon: 'pen',
    link: '/digital-marketing',
  },
  {
    id: 15,
    title: 'Online Reputation Management',
    slug: 'online-reputation-management',
    category: 'Digital Marketing',
    description:
      'Review generation, monitoring and response strategy to protect and grow trust.',
    icon: 'shield',
    link: '/digital-marketing',
  },
  {
    id: 16,
    title: 'Conversion Rate Optimization',
    slug: 'conversion-rate-optimization',
    category: 'Digital Marketing',
    description:
      'Analytics, experimentation and UX fixes that lift revenue from the traffic you already have.',
    icon: 'chart',
    link: '/digital-marketing',
  },
];

export const allServices = [...itServices, ...marketingServices];

export default allServices;
