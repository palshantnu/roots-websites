import { lazy } from 'react';

/* Pages are lazy-loaded so the initial bundle stays small. */
const Home = lazy(() => import('./pages/Home.jsx'));
const About = lazy(() => import('./pages/About.jsx'));
const Services = lazy(() => import('./pages/Services.jsx'));
const Portfolio = lazy(() => import('./pages/Portfolio.jsx'));
const CaseStudies = lazy(() => import('./pages/CaseStudies.jsx'));
const Blog = lazy(() => import('./pages/Blog.jsx'));
const BlogPost = lazy(() => import('./pages/BlogPost.jsx'));
const Contact = lazy(() => import('./pages/Contact.jsx'));
const NotFound = lazy(() => import('./pages/NotFound.jsx'));

/* Service detail pages */
const WebsiteDevelopment = lazy(() => import('./pages/services/WebsiteDevelopment.jsx'));
const SoftwareDevelopment = lazy(() => import('./pages/services/SoftwareDevelopment.jsx'));
const MobileAppDevelopment = lazy(() => import('./pages/services/MobileAppDevelopment.jsx'));
const CustomSoftware = lazy(() => import('./pages/services/CustomSoftware.jsx'));
const EcommerceSolutions = lazy(() => import('./pages/services/EcommerceSolutions.jsx'));
const DigitalMarketing = lazy(() => import('./pages/services/DigitalMarketing.jsx'));
const SEOServices = lazy(() => import('./pages/services/SEOServices.jsx'));
const SocialMediaMarketing = lazy(() => import('./pages/services/SocialMediaMarketing.jsx'));
const PaidAdvertising = lazy(() => import('./pages/services/PaidAdvertising.jsx'));

/**
 * Central route table (react-router v6 `useRoutes` format).
 * Keeping it as data makes it trivial to generate a sitemap later.
 */
export const routes = [
  { path: '/', element: <Home /> },
  { path: '/about', element: <About /> },

  { path: '/services', element: <Services /> },
  { path: '/services/website-development', element: <WebsiteDevelopment /> },
  { path: '/services/software-development', element: <SoftwareDevelopment /> },
  { path: '/services/mobile-app-development', element: <MobileAppDevelopment /> },
  { path: '/services/custom-software', element: <CustomSoftware /> },
  { path: '/services/ecommerce-solutions', element: <EcommerceSolutions /> },

  { path: '/digital-marketing', element: <DigitalMarketing /> },
  { path: '/digital-marketing/seo', element: <SEOServices /> },
  { path: '/digital-marketing/social-media-marketing', element: <SocialMediaMarketing /> },
  { path: '/digital-marketing/paid-advertising', element: <PaidAdvertising /> },

  { path: '/portfolio', element: <Portfolio /> },
  { path: '/case-studies', element: <CaseStudies /> },
  { path: '/blog', element: <Blog /> },
  { path: '/blog/:slug', element: <BlogPost /> },
  { path: '/contact', element: <Contact /> },

  { path: '*', element: <NotFound /> },
];
