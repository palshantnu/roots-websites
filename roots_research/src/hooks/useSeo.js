import { useEffect } from "react";
import { useLocation } from "react-router-dom";

const SITE_URL = (import.meta.env.VITE_SITE_URL || window.location.origin).replace(/\/$/, "");

/**
 * Creates, updates or (when `value` is empty) removes a head element that is
 * identified by one attribute, e.g. <meta name="description">.
 */
function setHeadTag(tagName, keyAttribute, key, valueAttribute, value) {
  let element = document.head.querySelector(`${tagName}[${keyAttribute}="${key}"]`);
  if (!value) {
    element?.remove();
    return;
  }
  if (!element) {
    element = document.createElement(tagName);
    element.setAttribute(keyAttribute, key);
    document.head.appendChild(element);
  }
  element.setAttribute(valueAttribute, value);
}

const setMetaName = (name, value) => setHeadTag("meta", "name", name, "content", value);
const setMetaProperty = (property, value) => setHeadTag("meta", "property", property, "content", value);

/**
 * Applies a page's SEO data (managed in the admin panel) to the document head:
 * title, meta description, canonical URL and Open Graph / Twitter tags.
 * Nothing changes until the page data has loaded, so the defaults in
 * index.html stay in place meanwhile.
 */
export function useSeo(page) {
  const { pathname } = useLocation();

  useEffect(() => {
    if (!page) return;

    const title = page.seo?.title || page.title;
    const description = page.seo?.description || page.description;
    const image = page.seo?.image;
    const url = `${SITE_URL}${pathname}`;

    if (title) document.title = title;

    setMetaName("description", description);
    setHeadTag("link", "rel", "canonical", "href", url);

    setMetaProperty("og:type", "website");
    setMetaProperty("og:title", title);
    setMetaProperty("og:description", description);
    setMetaProperty("og:url", url);
    setMetaProperty("og:image", image);

    setMetaName("twitter:card", image ? "summary_large_image" : "summary");
    setMetaName("twitter:title", title);
    setMetaName("twitter:description", description);
    setMetaName("twitter:image", image);
  }, [page, pathname]);
}
