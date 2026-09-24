import { placeholderImage } from '../utils/helpers';

/** Case studies — challenge / solution / results. Placeholder metrics. */
export const caseStudies = [
  {
    id: 1,
    slug: 'greenroute-organic-growth',
    client: 'GreenRoute',
    title: 'Turning a stalled blog into the primary lead channel',
    industry: 'CleanTech',
    image: placeholderImage('cs-greenroute', 1000, 640),
    challenge:
      'GreenRoute relied entirely on paid ads. Rising CPCs were squeezing margins and organic traffic had been flat for two years.',
    solution:
      'A technical SEO overhaul, a 24-article content programme mapped to buyer intent, and a digital-PR link campaign — paired with landing-page CRO.',
    technologies: ['Technical SEO', 'Content', 'Digital PR', 'CRO'],
    results: [
      { value: '210%', label: 'Organic traffic growth (9 months)' },
      { value: '150%', label: 'Increase in inbound leads' },
      { value: '-38%', label: 'Blended cost per lead' },
    ],
  },
  {
    id: 2,
    slug: 'meridian-operations-platform',
    client: 'Meridian Freight',
    title: 'Replacing seven spreadsheets with one operations platform',
    industry: 'Logistics',
    image: placeholderImage('cs-meridian', 1000, 640),
    challenge:
      'Dispatch, tracking and billing lived in disconnected spreadsheets. Invoicing lagged five days and errors were common.',
    solution:
      'A custom operations system with real-time dispatch, driver mobile app, automated billing and a management dashboard, rolled out in three modules.',
    technologies: ['React', 'Python', 'PostgreSQL', 'AWS'],
    results: [
      { value: '40%', label: 'Faster order-to-invoice cycle' },
      { value: '12 hrs', label: 'Admin time saved per week' },
      { value: '99.9%', label: 'Billing accuracy' },
    ],
  },
  {
    id: 3,
    slug: 'lumen-store-replatform',
    client: 'Lumen Store',
    title: 'A headless replatform that halved page load time',
    industry: 'Retail',
    image: placeholderImage('cs-lumen', 1000, 640),
    challenge:
      'A heavy theme and app stack pushed mobile load times past six seconds, and checkout abandonment was climbing.',
    solution:
      'A headless Next.js storefront on Shopify with a rebuilt mobile checkout, custom bundling and edge caching.',
    technologies: ['Next.js', 'Shopify', 'Stripe'],
    results: [
      { value: '-55%', label: 'Median page load time' },
      { value: '+27%', label: 'Mobile conversion rate' },
      { value: '+18%', label: 'Average order value' },
    ],
  },
  {
    id: 4,
    slug: 'careloop-patient-app',
    client: 'CareLoop',
    title: 'An offline-first patient app with 4.8-star ratings',
    industry: 'Healthcare',
    image: placeholderImage('cs-careloop', 1000, 640),
    challenge:
      'Patients missed doses and struggled to reach care teams. Connectivity in rural areas made web tools unreliable.',
    solution:
      'A React Native app with offline records, medication reminders, and secure asynchronous messaging with care teams.',
    technologies: ['React Native', 'Firebase', 'Node.js'],
    results: [
      { value: '+32%', label: 'Medication adherence' },
      { value: '4.8★', label: 'Average app store rating' },
      { value: '25k+', label: 'Active patients in year one' },
    ],
  },
];

export function getCaseStudy(slug) {
  return caseStudies.find((c) => c.slug === slug) || null;
}

export default caseStudies;
