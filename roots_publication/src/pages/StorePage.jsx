import { useState } from 'react'
import { ArrowRight, Search } from 'lucide-react'
import { useBooks, useCategories } from '../hooks'

export default function StorePage({ Layout, BookCard, PageIntro, EmptyState, cart, setCart }) {
  const params = new URLSearchParams(window.location.search)
  const [query, setQuery] = useState(params.get('q') || '')
  const [category, setCategory] = useState(params.get('category') || 'All categories')
  const [sort, setSort] = useState(params.get('sort') === 'new' ? 'new' : 'featured')
  const [wishlist, setWishlist] = useState([])
  const { categories } = useCategories()
  const { data: filtered } = useBooks({ q: query, category, sort })
  const add = book => setCart(cart.some(item => item.id === book.id) ? cart.map(item => item.id === book.id ? { ...item, quantity: item.quantity + 1 } : item) : [...cart, { ...book, quantity: 1 }])
  const count = cart.reduce((sum, item) => sum + item.quantity, 0)
  return <Layout cartCount={count}><main className="catalog-page"><PageIntro eyebrow="THE RTS BOOKSTORE" title="Find your next favourite book." copy="New voices, brave ideas and stories that stay with you." /><div className="store-toolbar"><label><Search size={17} /><input value={query} onChange={event => setQuery(event.target.value)} placeholder="Search by title or author" /></label><select value={category} onChange={event => setCategory(event.target.value)}><option>All categories</option>{categories.map(item => <option key={item}>{item}</option>)}</select><select value={sort} onChange={event => setSort(event.target.value)}><option value="featured">Featured</option><option value="new">New releases</option><option value="low">Price: low to high</option><option value="high">Price: high to low</option></select></div><div className="store-layout"><aside><span className="eyebrow">BROWSE BY GENRE</span>{categories.map(item => <button className={category === item ? 'selected' : ''} onClick={() => setCategory(item)} key={item}>{item}<ArrowRight size={14} /></button>)}</aside><div><div className="result-line"><span>{filtered.length} books found</span><a href="/categories">View all categories <ArrowRight size={14} /></a></div>{filtered.length ? <div className="book-grid-new">{filtered.map(book => <BookCard key={book.id} book={book} onAdd={add} onWish={id => setWishlist(wishlist.includes(id) ? wishlist.filter(value => value !== id) : [...wishlist, id])} wished={wishlist.includes(book.id)} />)}</div> : <EmptyState title="No books found." copy="Try a different title, author or category." />}</div></div></main></Layout>
}
