/**
 * Navigation model shared by Navbar (desktop + mobile) and Footer.
 * `mega` powers the Services dropdown.
 */

export const primaryNav = [
  { label: 'Home', to: '/' },
  { label: 'About Us', to: '/about' },
  {
    label: 'Services',
    to: '/services',
    mega: [
      {
        title: 'IT Services',
        links: [
          { label: 'Website Development', to: '/services/website-development', icon: 'monitor' },
          { label: 'Software Development', to: '/services/software-development', icon: 'code' },
          { label: 'Mobile App Development', to: '/services/mobile-app-development', icon: 'smartphone' },
          { label: 'Custom Software Solutions', to: '/services/custom-software', icon: 'settings' },
          { label: 'E-Commerce Solutions', to: '/services/ecommerce-solutions', icon: 'cart' },
        ],
      },
      {
        title: 'Digital Marketing',
        links: [
          { label: 'Digital Marketing', to: '/digital-marketing', icon: 'trending' },
          { label: 'SEO', to: '/digital-marketing/seo', icon: 'search' },
          { label: 'Social Media Marketing', to: '/digital-marketing/social-media-marketing', icon: 'share' },
          { label: 'Paid Advertising', to: '/digital-marketing/paid-advertising', icon: 'target' },
        ],
      },
    ],
  },
  { label: 'Portfolio', to: '/portfolio' },
  { label: 'Case Studies', to: '/case-studies' },
  { label: 'Blog', to: '/blog' },
  { label: 'Contact Us', to: '/contact' },
];

export const footerNav = {
  company: {
    title: 'Company',
    links: [
      { label: 'About Us', to: '/about' },
      { label: 'Portfolio', to: '/portfolio' },
      { label: 'Case Studies', to: '/case-studies' },
      { label: 'Blog', to: '/blog' },
      { label: 'Contact', to: '/contact' },
    ],
  },
  services: {
    title: 'Services',
    links: [
      { label: 'Website Development', to: '/services/website-development' },
      { label: 'Software Development', to: '/services/software-development' },
      { label: 'Mobile App Development', to: '/services/mobile-app-development' },
      { label: 'E-Commerce Solutions', to: '/services/ecommerce-solutions' },
      { label: 'SEO', to: '/digital-marketing/seo' },
      { label: 'Digital Marketing', to: '/digital-marketing' },
    ],
  },
};

export default primaryNav;
