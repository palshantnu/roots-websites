import { placeholderImage } from '../utils/helpers';

/**
 * Blog posts. `body` is an array of blocks so it can be swapped for
 * portable-text / markdown from a CMS without changing <BlogPost>.
 */
export const blogCategories = [
  'All',
  'Web Development',
  'Software Development',
  'Mobile Apps',
  'Digital Marketing',
  'SEO',
  'Technology',
];

export const blogs = [
  {
    id: 1,
    slug: 'core-web-vitals-2026',
    title: 'Core Web Vitals in 2026: a practical checklist',
    category: 'Web Development',
    date: '2026-08-18',
    author: 'Priya Nair',
    excerpt:
      'What actually moves LCP, INP and CLS on real projects — and the measurement mistakes that hide regressions.',
    image: placeholderImage('blog-cwv', 1000, 560),
    body: [
      { type: 'p', text: 'Core Web Vitals reward the same things good engineering always has: send less, send it sooner, and do not shift the layout under the user. This post is the checklist we run on every build.' },
      { type: 'h2', text: 'Largest Contentful Paint' },
      { type: 'p', text: 'Preload the hero image and font, serve modern formats, and make sure the LCP element is not lazy-loaded. On most sites, an unoptimised hero image is the single biggest offender.' },
      { type: 'h2', text: 'Interaction to Next Paint' },
      { type: 'p', text: 'Break up long tasks, defer non-critical JavaScript, and keep third-party tags on a leash. Measure with a real device, not a fast laptop.' },
      { type: 'h2', text: 'Cumulative Layout Shift' },
      { type: 'ul', items: ['Always set width and height on media', 'Reserve space for embeds and ads', 'Avoid inserting content above existing content'] },
    ],
  },
  {
    id: 2,
    slug: 'react-native-vs-native',
    title: 'React Native vs native: how we actually decide',
    category: 'Mobile Apps',
    date: '2026-07-30',
    author: 'Daniel Osei',
    excerpt:
      'A decision framework for choosing between React Native and fully native — beyond the usual talking points.',
    image: placeholderImage('blog-rn', 1000, 560),
    body: [
      { type: 'p', text: 'The honest answer is “it depends”, but the dependencies are predictable. Here is the framework we use with clients.' },
      { type: 'h2', text: 'Choose React Native when' },
      { type: 'ul', items: ['You need iOS and Android from one team', 'The UI is mostly standard components and lists', 'Time to market matters more than squeezing the last 5% of performance'] },
      { type: 'h2', text: 'Choose native when' },
      { type: 'ul', items: ['You need heavy graphics, AR or camera pipelines', 'Deep OS integration is core to the product', 'You already have strong native teams'] },
    ],
  },
  {
    id: 3,
    slug: 'headless-cms-tradeoffs',
    title: 'The real trade-offs of going headless',
    category: 'Web Development',
    date: '2026-07-12',
    author: 'Priya Nair',
    excerpt:
      'Headless CMS gives you flexibility and speed — at the cost of more moving parts. When it is worth it.',
    image: placeholderImage('blog-headless', 1000, 560),
    body: [
      { type: 'p', text: 'Headless is not automatically better. It is a trade: you gain a clean content API and front-end freedom, and you take on preview infrastructure, build pipelines and more services to run.' },
      { type: 'h2', text: 'Good fit' },
      { type: 'p', text: 'Content-heavy sites, multi-channel content, teams that want a modern front-end stack, and projects where performance is a KPI.' },
      { type: 'h2', text: 'Poor fit' },
      { type: 'p', text: 'Small brochure sites with one editor and no performance pressure — a well-configured traditional CMS ships faster.' },
    ],
  },
  {
    id: 4,
    slug: 'seo-content-briefs',
    title: 'How to write an SEO content brief writers do not hate',
    category: 'SEO',
    date: '2026-06-25',
    author: 'Marcus Feld',
    excerpt:
      'A brief template that gives writers intent, structure and sources without turning them into keyword-stuffing machines.',
    image: placeholderImage('blog-briefs', 1000, 560),
    body: [
      { type: 'p', text: 'A good brief answers three questions: who is searching this, what do they need to walk away with, and what proof should the article include?' },
      { type: 'h2', text: 'What belongs in the brief' },
      { type: 'ul', items: ['Primary query and the intent behind it', 'Target reader and their level of knowledge', 'Outline with H2/H3 suggestions', 'Sources, data points and internal links to include', 'What “done” looks like'] },
    ],
  },
  {
    id: 5,
    slug: 'automating-back-office',
    title: 'Automating the back office without a big-bang project',
    category: 'Software Development',
    date: '2026-06-08',
    author: 'Elena Rossi',
    excerpt:
      'How to sequence automation so each release pays for the next, instead of betting everything on one launch.',
    image: placeholderImage('blog-automation', 1000, 560),
    body: [
      { type: 'p', text: 'The failure mode for internal software is the 12-month project that launches all at once. Sequencing by value avoids it.' },
      { type: 'h2', text: 'The sequence' },
      { type: 'ul', items: ['Instrument the current process to get a baseline', 'Automate the single most painful step first', 'Ship it, measure hours saved, use that to fund the next step', 'Only then integrate adjacent systems'] },
    ],
  },
  {
    id: 6,
    slug: 'ppc-tracking-2026',
    title: 'Paid media tracking that survives privacy changes',
    category: 'Digital Marketing',
    date: '2026-05-20',
    author: 'Marcus Feld',
    excerpt:
      'Server-side tagging, consent mode and first-party data — a pragmatic setup for reliable conversion tracking.',
    image: placeholderImage('blog-ppc', 1000, 560),
    body: [
      { type: 'p', text: 'Browser-side pixels are a shrinking signal. A durable setup leans on server-side events and your own first-party data.' },
      { type: 'h2', text: 'The stack we use' },
      { type: 'ul', items: ['Server-side GTM for event collection', 'Consent mode configured properly', 'Offline conversion imports for long sales cycles', 'A clean data layer as the single source'] },
    ],
  },
  {
    id: 7,
    slug: 'design-systems-small-teams',
    title: 'Design systems for small teams: how light is too light?',
    category: 'Technology',
    date: '2026-05-02',
    author: 'Priya Nair',
    excerpt:
      'You do not need a 300-component library. You need tokens, a dozen primitives and the discipline to use them.',
    image: placeholderImage('blog-ds', 1000, 560),
    body: [
      { type: 'p', text: 'For a team of five, a full design system is overhead. A “design kit” — tokens plus a small set of primitives — gets 80% of the benefit.' },
      { type: 'h2', text: 'The minimum viable kit' },
      { type: 'ul', items: ['Colour, spacing, radius and type tokens', 'Button, Input, Card, Modal, Tabs', 'One documented layout grid', 'A usage doc, not a Storybook cathedral'] },
    ],
  },
  {
    id: 8,
    slug: 'shipping-accessible-by-default',
    title: 'Shipping accessible interfaces by default',
    category: 'Web Development',
    date: '2026-04-15',
    author: 'Daniel Osei',
    excerpt:
      'Accessibility is cheap when it is a habit and expensive when it is an audit. Build the habits into your components.',
    image: placeholderImage('blog-a11y', 1000, 560),
    body: [
      { type: 'p', text: 'Most accessibility issues trace back to a handful of components. Fix them once, at the primitive level, and every page inherits the fix.' },
      { type: 'h2', text: 'High-leverage fixes' },
      { type: 'ul', items: ['Focus-visible styles on every interactive element', 'Labels tied to inputs, errors announced', 'Modal focus trap and escape handling', 'Colour contrast checked in the token palette'] },
    ],
  },
];

export function getBlog(slug) {
  return blogs.find((b) => b.slug === slug) || null;
}

export default blogs;
