// Same fetch client as roots_publication/src/api.js and
// roots_research/src/lib/api.js, pointed at the IT endpoints. The backend
// only ever returns IT content here.
const BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api/it';

async function request(path, options = {}) {
  const response = await fetch(`${BASE_URL}${path}`, {
    headers: {
      Accept: 'application/json',
      ...(options.body instanceof FormData ? {} : { 'Content-Type': 'application/json' }),
      ...options.headers,
    },
    ...options,
  });
  const isJson = response.headers.get('content-type')?.includes('application/json');
  const payload = isJson ? await response.json() : null;
  if (!response.ok) {
    const error = new Error(payload?.message || 'Request failed');
    error.status = response.status;
    error.errors = payload?.errors;
    throw error;
  }
  return payload;
}

const get = (path) => request(path).then((payload) => payload.data);

export const api = {
  settings: () => get('/settings'),
  pages: () => get('/pages'),
  sections: () => get('/sections'),
  services: () => get('/services'),
  projects: () => get('/projects'),
  caseStudies: () => get('/case-studies'),
  testimonials: () => get('/testimonials'),
  faqs: () => get('/faqs'),
  posts: () => get('/posts'),
};
