import { BookOpen } from 'lucide-react'
import { useBooks } from '../hooks'
export default function CategoryDetailPage({ Layout, PageIntro, BookCard, category, cart, setCart }) {
  const title = category.charAt(0).toUpperCase() + category.slice(1)
  const { data: filtered } = useBooks({ category })
  const add = item => setCart(cart.some(book => book.id === item.id) ? cart.map(book => book.id === item.id ? { ...book, quantity: book.quantity + 1 } : book) : [...cart, { ...item, quantity: 1 }])
  return <Layout cartCount={cart.reduce((sum, item) => sum + item.quantity, 0)}><main className="catalog-page"><PageIntro eyebrow="RTS BOOK CATEGORY" title={`${title} books.`} copy={`Explore thoughtful ${category} titles from the RTS Publication catalogue.`} />{filtered.length ? <div className="category-detail-grid">{filtered.map(book => <BookCard key={book.id} book={book} onAdd={add} onWish={() => {}} />)}</div> : <div className="empty-state"><BookOpen size={36} /><h2>No books in this category yet.</h2><p>Our catalogue is growing. Browse every title while we add more.</p><a className="button" href="/books">Browse all books</a></div>}</main></Layout>
}
