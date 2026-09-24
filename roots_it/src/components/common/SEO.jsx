import { Helmet } from 'react-helmet-async';
import { useLocation } from 'react-router-dom';
import { site } from '../../data/site';

/**
 * Per-page SEO. Drop <SEO title="..." description="..." /> at the top of any
 * page. Falls back to sensible site-wide defaults.
 */
export default function SEO({
  title,
  description = site.description,
  image,
  type = 'website',
  noindex = false,
}) {
  const { pathname } = useLocation();
  const baseUrl = (import.meta.env.VITE_SITE_URL || 'https://www.rootstechnology.com').replace(/\/$/, '');
  const url = `${baseUrl}${pathname}`;
  const fullTitle = title ? `${title} | ${site.name}` : `${site.name} — ${site.tagline}`;

  return (
    <Helmet>
      <title>{fullTitle}</title>
      <meta name="description" content={description} />
      <link rel="canonical" href={url} />
      {noindex && <meta name="robots" content="noindex,nofollow" />}

      <meta property="og:type" content={type} />
      <meta property="og:site_name" content={site.name} />
      <meta property="og:title" content={fullTitle} />
      <meta property="og:description" content={description} />
      <meta property="og:url" content={url} />
      {image && <meta property="og:image" content={image} />}

      <meta name="twitter:card" content={image ? 'summary_large_image' : 'summary'} />
      <meta name="twitter:title" content={fullTitle} />
      <meta name="twitter:description" content={description} />
      {image && <meta name="twitter:image" content={image} />}
    </Helmet>
  );
}
