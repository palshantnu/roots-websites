import { useCallback, useEffect, useState } from "react";
import { api } from "../lib/api";
import { useSeo } from "./useSeo";

// Cached fetch hook, same approach as roots_publication/src/hooks.js: each
// resource is requested once per visit and shared by every component.
const cache = new Map();
const pending = new Map();

function load(key, fetcher) {
  if (!pending.has(key)) {
    pending.set(
      key,
      fetcher()
        .then((result) => {
          cache.set(key, result);
          return result;
        })
        .finally(() => pending.delete(key))
    );
  }
  return pending.get(key);
}

function useFetch(key, fetcher, { fallback = [] } = {}) {
  const cached = cache.get(key);
  const [data, setData] = useState(cached ?? fallback);
  const [loading, setLoading] = useState(!cached);
  const [error, setError] = useState(null);
  const [attempt, setAttempt] = useState(0);

  useEffect(() => {
    let active = true;
    if (cache.has(key)) {
      setData(cache.get(key));
      setLoading(false);
      return undefined;
    }
    setLoading(true);
    load(key, fetcher)
      .then((result) => {
        if (!active) return;
        setData(result);
        setError(null);
      })
      .catch((err) => active && setError(err))
      .finally(() => active && setLoading(false));
    return () => {
      active = false;
    };
    // `fetcher` is derived from `key`, so `key` alone identifies the request.
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [key, attempt]);

  const reload = useCallback(() => {
    cache.delete(key);
    setError(null);
    setAttempt((n) => n + 1);
  }, [key]);

  return { data, loading, error, reload };
}

export function useSettings() {
  const { data, loading } = useFetch("settings", api.settings, { fallback: null });
  return { settings: data, loading };
}

/**
 * All section lists, grouped by key. Returns one list: useSection("hero_stats").
 */
export function useSection(key) {
  const { data, loading, error } = useFetch("sections", api.sections, { fallback: {} });
  return { items: data?.[key] ?? [], loading, error };
}

/**
 * Hero, call-to-action and SEO copy for one page. Also applies the page's SEO
 * tags, so every page that calls this is SEO-enabled.
 */
export function usePage(slug) {
  const { data, loading } = useFetch("pages", api.pages);
  const page = data.find((p) => p.slug === slug) ?? null;
  useSeo(page);
  return { page, loading };
}

export function useServices() {
  return useFetch("services", api.services);
}

export function useTestimonials() {
  return useFetch("testimonials", api.testimonials);
}

export function useFaqs() {
  return useFetch("faqs", api.faqs);
}

export function usePosts() {
  return useFetch("posts", api.posts);
}

export function useSamples() {
  return useFetch("samples", api.samples);
}
