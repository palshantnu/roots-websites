const BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api/publication'

async function request(path, options = {}) {
  const response = await fetch(`${BASE_URL}${path}`, {
    headers: { Accept: 'application/json', ...(options.body instanceof FormData ? {} : { 'Content-Type': 'application/json' }), ...options.headers },
    ...options,
  })
  const isJson = response.headers.get('content-type')?.includes('application/json')
  const payload = isJson ? await response.json() : null
  if (!response.ok) {
    const error = new Error(payload?.message || 'Request failed')
    error.status = response.status
    error.errors = payload?.errors
    throw error
  }
  return payload
}

const get = path => request(path).then(payload => payload.data)

export const api = {
  categories: () => get('/categories'),
  category: slug => get(`/categories/${slug}`),
  authors: () => get('/authors'),
  author: slug => get(`/authors/${slug}`),
  books: (params = {}) => get(`/books?${new URLSearchParams(params)}`),
  book: slug => get(`/books/${slug}`),
  services: () => get('/services'),
  service: slug => get(`/services/${slug}`),
  packages: () => get('/packages'),
  packagePage: slug => get(`/package-pages/${slug}`),
  posts: () => get('/posts'),
  post: slug => get(`/posts/${slug}`),
  faqs: () => get('/faqs'),
  settings: () => get('/settings'),
  search: q => request(`/search?${new URLSearchParams({ q })}`),
  submitContact: data => request('/contact', { method: 'POST', body: JSON.stringify(data) }),
  submitPublishEnquiry: formData => request('/publish-enquiries', { method: 'POST', body: formData }),
  subscribeNewsletter: email => request('/newsletter', { method: 'POST', body: JSON.stringify({ email }) }),
  placeOrder: data => request('/orders', { method: 'POST', body: JSON.stringify(data) }),
}
