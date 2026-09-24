import { useEffect, useState } from 'react'
import { api } from './api'

const cache = new Map()

function useFetch(key, fetcher, { list = true, skip = false } = {}) {
  const cached = key ? cache.get(key) : undefined
  const [data, setData] = useState(cached ?? (list ? [] : null))
  const [loading, setLoading] = useState(!cached && !skip)
  const [error, setError] = useState(null)

  useEffect(() => {
    if (skip || !key) {
      setLoading(false)
      return
    }
    let active = true
    if (cache.has(key)) {
      setData(cache.get(key))
      setLoading(false)
      return
    }
    setLoading(true)
    fetcher()
      .then(result => {
        if (!active) return
        cache.set(key, result)
        setData(result)
        setError(null)
      })
      .catch(err => active && setError(err))
      .finally(() => active && setLoading(false))
    return () => { active = false }
  }, [key, skip])

  return { data, loading, error }
}

export function useCategories() {
  const { data, loading } = useFetch('categories', api.categories)
  return { categories: data.map(c => c.name), loading }
}

export function useAuthors() {
  return useFetch('authors', api.authors)
}

export function useAuthor(slug) {
  const { data, loading, error } = useFetch(slug ? `author:${slug}` : null, () => api.author(slug), { list: false, skip: !slug })
  return { author: data, loading, error }
}

export function useBooks(params = {}) {
  const key = `books:${JSON.stringify(params)}`
  return useFetch(key, () => api.books(params))
}

export function useBook(slug) {
  const { data, loading, error } = useFetch(slug ? `book:${slug}` : null, () => api.book(slug), { list: false, skip: !slug })
  return { book: data, loading, error }
}

export function useServices() {
  return useFetch('services', api.services)
}

export function useService(slug) {
  const { data, loading, error } = useFetch(slug ? `service:${slug}` : null, () => api.service(slug), { list: false, skip: !slug })
  return { service: data, loading, error }
}

export function usePackages() {
  return useFetch('packages', api.packages)
}

export function usePackagePage(slug) {
  const { data, loading, error } = useFetch(slug ? `package-page:${slug}` : null, () => api.packagePage(slug), { list: false, skip: !slug })
  return { page: data, loading, error }
}

export function usePosts() {
  return useFetch('posts', api.posts)
}

export function usePost(slug) {
  const { data, loading, error } = useFetch(slug ? `post:${slug}` : null, () => api.post(slug), { list: false, skip: !slug })
  return { post: data, loading, error }
}

export function useFaqs() {
  return useFetch('faqs', api.faqs)
}

export function useSettings() {
  const { data, loading } = useFetch('settings', api.settings, { list: false })
  return { settings: data, loading }
}

export function useSearch(query) {
  const [result, setResult] = useState({ books: [] })
  const [loading, setLoading] = useState(false)

  useEffect(() => {
    if (!query) {
      setResult({ books: [] })
      return
    }
    let active = true
    setLoading(true)
    api.search(query)
      .then(payload => active && setResult(payload))
      .finally(() => active && setLoading(false))
    return () => { active = false }
  }, [query])

  return { books: result.books ?? [], loading }
}
