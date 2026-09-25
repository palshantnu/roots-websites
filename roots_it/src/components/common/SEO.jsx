import { Helmet } from 'react-helmet-async';
import { useLocation } from 'react-router-dom';
import { useSettings } from '../../hooks/useApi';

/**
 * Per-page SEO. Pass the page from usePage() to use its admin-managed meta
 * title, description and share image, or pass title/description directly
 * (e.g. blog posts). Falls back to the site-wide settings.
 */
export default function SEO({ page, title, description, image, type = 'website', noindex = false }) {
  const { pathname } = useLocation();
  const { settings } = useSettings();

  const siteName = settings?.siteName ?? '';
  const pageTitle = title ?? page?.seo?.title ?? null;
  const metaDescription = description ?? page?.seo?.description ?? page?.description ?? settings?.tagline ?? '';
  const shareImage = image ?? page?.seo?.image ?? null;

  const baseUrl = (import.meta.env.VITE_SITE_URL || 'https://www.rootstechnology.com').replace(/\/$/, '');
  const url = `${baseUrl}${pathname}`;
  const fullTitle = pageTitle
    ? `${pageTitle}${siteName ? ` | ${siteName}` : ''}`
    : [siteName, settings?.tagline].filter(Boolean).join(' — ');

  // Keep the index.html defaults until the settings have loaded.
  if (!settings) return null;

  return (
    <Helmet>
      <title>{fullTitle}</title>
      {metaDescription && <meta name="description" content={metaDescription} />}
      <link rel="canonical" href={url} />
      {noindex && <meta name="robots" content="noindex,nofollow" />}

      <meta property="og:type" content={type} />
      <meta property="og:site_name" content={siteName} />
      <meta property="og:title" content={fullTitle} />
      {metaDescription && <meta property="og:description" content={metaDescription} />}
      <meta property="og:url" content={url} />
      {shareImage && <meta property="og:image" content={shareImage} />}

      <meta name="twitter:card" content={shareImage ? 'summary_large_image' : 'summary'} />
      <meta name="twitter:title" content={fullTitle} />
      {metaDescription && <meta name="twitter:description" content={metaDescription} />}
      {shareImage && <meta name="twitter:image" content={shareImage} />}
    </Helmet>
  );
}
