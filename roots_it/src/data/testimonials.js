import { avatarImage } from '../utils/helpers';

/** Client testimonials for the homepage slider. Dummy data. */
export const testimonials = [
  {
    id: 1,
    name: 'Sarah Whitfield',
    role: 'VP Product',
    company: 'NorthPeak Analytics',
    rating: 5,
    avatar: avatarImage('sarah-whitfield'),
    quote:
      'Roots Technology shipped our analytics platform on time and under budget. Two years on, it still scales without us thinking about it. Genuinely a rare experience with an agency.',
  },
  {
    id: 2,
    name: 'James Okoro',
    role: 'Founder & CEO',
    company: 'Lumen Store',
    rating: 5,
    avatar: avatarImage('james-okoro'),
    quote:
      'Our headless replatform cut load times in half and mobile conversion jumped 27%. They cared about the numbers as much as we did.',
  },
  {
    id: 3,
    name: 'Dr. Anita Rao',
    role: 'Chief Medical Officer',
    company: 'CareLoop',
    rating: 5,
    avatar: avatarImage('anita-rao'),
    quote:
      'The team understood that reliability in a healthcare app is non-negotiable. Offline-first was handled beautifully and our patients notice the difference.',
  },
  {
    id: 4,
    name: 'Tom Bergström',
    role: 'Operations Director',
    company: 'Meridian Freight',
    rating: 5,
    avatar: avatarImage('tom-bergstrom'),
    quote:
      'They replaced seven spreadsheets with one system, rolled out in modules so we were never disrupted. Invoicing went from five days to same-day.',
  },
  {
    id: 5,
    name: 'Leah Kim',
    role: 'Head of Growth',
    company: 'GreenRoute',
    rating: 5,
    avatar: avatarImage('leah-kim'),
    quote:
      'Organic went from an afterthought to our top lead channel in nine months. The reporting is honest — we always know exactly what is working.',
  },
  {
    id: 6,
    name: 'Michael Grant',
    role: 'Managing Partner',
    company: 'Atlas Legal',
    rating: 5,
    avatar: avatarImage('michael-grant'),
    quote:
      'A corporate site for a firm our size is a political project as much as a technical one. Roots handled both with patience and a very polished result.',
  },
];

export default testimonials;
