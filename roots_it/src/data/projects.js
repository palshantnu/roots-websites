import { placeholderImage } from '../utils/helpers';

/**
 * Portfolio projects. `category` values feed the Portfolio filter bar.
 * Shape mirrors a CMS "projects" collection.
 */
export const projects = [
  {
    id: 1,
    name: 'NorthPeak Analytics',
    slug: 'northpeak-analytics',
    category: 'Software',
    industry: 'SaaS / Data',
    technologies: ['React', 'TypeScript', 'Node.js', 'PostgreSQL'],
    description:
      'A multi-tenant analytics platform with self-serve dashboards, scheduled reports and role-based access for 4,000+ users.',
    image: placeholderImage('northpeak', 900, 600),
    year: 2025,
    featured: true,
  },
  {
    id: 2,
    name: 'Lumen Store',
    slug: 'lumen-store',
    category: 'E-Commerce',
    industry: 'Retail / Lifestyle',
    technologies: ['Next.js', 'Shopify', 'Stripe'],
    description:
      'Headless Shopify storefront with custom bundling, a 1.2s median load time and a rebuilt mobile checkout.',
    image: placeholderImage('lumen', 900, 600),
    year: 2025,
    featured: true,
  },
  {
    id: 3,
    name: 'CareLoop',
    slug: 'careloop',
    category: 'Mobile Apps',
    industry: 'Healthcare',
    technologies: ['React Native', 'Firebase', 'Node.js'],
    description:
      'A patient companion app with medication reminders, secure messaging and offline-first records.',
    image: placeholderImage('careloop', 900, 600),
    year: 2024,
    featured: true,
  },
  {
    id: 4,
    name: 'Meridian Freight',
    slug: 'meridian-freight',
    category: 'Software',
    industry: 'Logistics',
    technologies: ['React', 'Python', 'PostgreSQL', 'AWS'],
    description:
      'A logistics operations system unifying dispatch, tracking and billing — replacing seven spreadsheets.',
    image: placeholderImage('meridian', 900, 600),
    year: 2024,
    featured: true,
  },
  {
    id: 5,
    name: 'Atlas Legal',
    slug: 'atlas-legal',
    category: 'Website',
    industry: 'Professional Services',
    technologies: ['Next.js', 'Sanity CMS'],
    description:
      'A corporate website for a 120-lawyer firm with a headless CMS, careers portal and multi-language support.',
    image: placeholderImage('atlas', 900, 600),
    year: 2025,
    featured: true,
  },
  {
    id: 6,
    name: 'GreenRoute',
    slug: 'greenroute',
    category: 'Digital Marketing',
    industry: 'CleanTech',
    technologies: ['SEO', 'Google Ads', 'Content'],
    description:
      'A 9-month growth programme that grew organic traffic 210% and cut cost per lead by 38%.',
    image: placeholderImage('greenroute', 900, 600),
    year: 2025,
    featured: true,
  },
  {
    id: 7,
    name: 'Fable Studio',
    slug: 'fable-studio',
    category: 'Website',
    industry: 'Creative Agency',
    technologies: ['React', 'Framer Motion'],
    description:
      'An animation-rich portfolio site with case studies, a CMS and a sub-1.5s Largest Contentful Paint.',
    image: placeholderImage('fable', 900, 600),
    year: 2024,
    featured: false,
  },
  {
    id: 8,
    name: 'Orbit Rewards',
    slug: 'orbit-rewards',
    category: 'Mobile Apps',
    industry: 'Retail / Loyalty',
    technologies: ['React Native', 'Node.js', 'MongoDB'],
    description:
      'A loyalty app with digital cards, push offers and a merchant dashboard, launched on iOS and Android.',
    image: placeholderImage('orbit', 900, 600),
    year: 2025,
    featured: false,
  },
];

export const projectCategories = [
  'All',
  'Website',
  'Mobile Apps',
  'Software',
  'E-Commerce',
  'Digital Marketing',
];

export const featuredProjects = projects.filter((p) => p.featured);

export default projects;
