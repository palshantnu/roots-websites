import { useCallback, useEffect, useState } from 'react';
import { api } from '../lib/api';

// Cached fetch hook, same approach as roots_research/src/hooks/useApi.js:
// each resource is requested once per visit and shared by every component,
// so e.g. all sections of every page come from one /sections request.
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
  const { data, loading, error } = useFetch('settings', api.settings, { fallback: null });
  return { settings: data, loading, error };
}

/** Hero, call-to-action, SEO and content blocks for one page. */
export function usePage(slug) {
  const { data, loading, error, reload } = useFetch('pages', api.pages);
  return { page: data.find((p) => p.slug === slug) ?? null, loading, error, reload };
}

/** One section list, e.g. useSection('stats'). All sections share one request. */
export function useSection(key) {
  const { data, loading, error, reload } = useFetch('sections', api.sections, { fallback: {} });
  return { items: data?.[key] ?? [], loading, error, reload };
}

export function useServices() {
  return useFetch('services', api.services);
}

export function useProjects() {
  return useFetch('projects', api.projects);
}

export function useCaseStudies() {
  return useFetch('case-studies', api.caseStudies);
}

export function useTestimonials() {
  return useFetch('testimonials', api.testimonials);
}

export function useFaqs() {
  return useFetch('faqs', api.faqs);
}

export function usePosts() {
  return useFetch('posts', api.posts);
}
