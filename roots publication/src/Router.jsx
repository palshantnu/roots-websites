import { useEffect, useState } from 'react'
import { authors, books } from './data'
import { Layout, BookCard, PageIntro, EmptyState, Home, StandardPage } from './App'
import StorePage from './pages/StorePage'
import PublishPage from './pages/PublishPage'
import AboutPage from './pages/AboutPage'
import ServicesPage from './pages/ServicesPage'
import PackagesPage from './pages/PackagesPage'
import BlogPage from './pages/BlogPage'
import AuthorsPage from './pages/AuthorsPage'
import CategoriesPage from './pages/CategoriesPage'
import AccountPage from './pages/AccountPage'
import ServiceDetailPage from './pages/ServiceDetailPage'
import PackageDetailPage from './pages/PackageDetailPage'
import CategoryDetailPage from './pages/CategoryDetailPage'
import CartPage from './pages/CartPage'
import CheckoutPage from './pages/CheckoutPage'
import ContactPage from './pages/ContactPage'
import FaqPage from './pages/FaqPage'
import SearchPage from './pages/SearchPage'
import BlogDetailPage from './pages/BlogDetailPage'
import BookDetailPage from './pages/BookDetailPage'
import AuthorDetailPage from './pages/AuthorDetailPage'

export default function Router() {
  const [cart, setCart] = useState(() => JSON.parse(localStorage.getItem('rts-cart') || '[]'))
  useEffect(() => localStorage.setItem('rts-cart', JSON.stringify(cart)), [cart])
  const path = window.location.pathname.split('/').filter(Boolean)
  const route = path[0] || 'home'
  useEffect(() => { document.title = route === 'home' ? 'RTS Publication | Your story. Our craft.' : `${route.replaceAll('-', ' ')} | RTS Publication` }, [route])
  const shared = { Layout, PageIntro, BookCard, EmptyState }
  if (route === 'home') return <Home cart={cart} setCart={setCart} />
  if (route === 'books') return path[1] ? <BookDetailPage slug={path[1]} cart={cart} setCart={setCart} /> : <StorePage {...shared} cart={cart} setCart={setCart} />
  if (route === 'authors') return path[1] ? <AuthorDetailPage slug={path[1]} cart={cart} setCart={setCart} /> : <AuthorsPage {...shared} />
  if (route === 'cart') return <CartPage cart={cart} setCart={setCart} />
  if (route === 'checkout') return <CheckoutPage cart={cart} />
  if (route === 'publish') return <PublishPage {...shared} />
  if (route === 'search') return <SearchPage cart={cart} setCart={setCart} />
  if (route === 'categories') return path[1] ? <CategoryDetailPage {...shared} category={path[1]} cart={cart} setCart={setCart} /> : <CategoriesPage {...shared} />
  if (route === 'account') return <AccountPage Layout={Layout} />
  if (route === 'author') return <AccountPage Layout={Layout} author />
  if (route === 'contact') return <ContactPage />
  if (route === 'faq') return <FaqPage />
  if (route === 'blog') return path[1] ? <BlogDetailPage slug={path[1]} /> : <BlogPage {...shared} />
  if (route === 'about') return <AboutPage {...shared} />
  if (route === 'services') return path[1] ? <ServiceDetailPage {...shared} slug={path[1]} /> : <ServicesPage {...shared} />
  if (route === 'packages') return <PackagesPage {...shared} />
  if (['paperback-packages', 'ebook-packages', 'childrenbook-packages', 'self-publishing-usa', 'book-publishing-uk'].includes(route)) return <PackageDetailPage {...shared} slug={route} />
  return <StandardPage route={route} />
}
