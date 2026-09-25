// Same fetch client as roots_publication/src/api.js, pointed at the
// Research endpoints. The backend only ever returns Research content here.
const BASE_URL = import.meta.env.VITE_API_URL || "http://localhost:8000/api/research";

async function request(path, options = {}) {
  const response = await fetch(`${BASE_URL}${path}`, {
    headers: { Accept: "application/json", ...(options.body instanceof FormData ? {} : { "Content-Type": "application/json" }), ...options.headers },
    ...options,
  });
  const isJson = response.headers.get("content-type")?.includes("application/json");
  const payload = isJson ? await response.json() : null;
  if (!response.ok) {
    const error = new Error(payload?.message || "Request failed");
    error.status = response.status;
    error.errors = payload?.errors;
    throw error;
  }
  return payload;
}

const get = (path) => request(path).then((payload) => payload.data);

export const api = {
  settings: () => get("/settings"),
  pages: () => get("/pages"),
  sections: () => get("/sections"),
  services: () => get("/services"),
  testimonials: () => get("/testimonials"),
  faqs: () => get("/faqs"),
  posts: () => get("/posts"),
  samples: (params = {}) => get(`/samples?${new URLSearchParams(params)}`),
  sample: (slug) => get(`/samples/${slug}`),
};
