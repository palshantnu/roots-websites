import { useState } from 'react'
import { ArrowRight, BookOpen, Check, Heart, Minus, Plus, ShoppingBag, Star, User } from 'lucide-react'
import { api } from './api'
import { useAuthor, useAuthors, useBook, useBooks, useFaqs, usePackages, usePost, usePosts, useSearch, useSettings } from './hooks'
import SiteHeader from './components/SiteHeader'

const img = book => book.coverImage

function Button({ children, href, onClick, secondary = false, type = 'button', disabled = false }) {
  return href
    ? <a className={`button ${secondary ? 'button-secondary' : ''}`} href={href}>{children}<ArrowRight size={16} /></a>
    : <button type={type} className={`button ${secondary ? 'button-secondary' : ''}`} onClick={onClick} disabled={disabled}>{children}<ArrowRight size={16} /></button>
}

function Footer() {
  const { settings } = useSettings()
  const [email, setEmail] = useState('')
  const [subscribed, setSubscribed] = useState(false)
  const subscribe = async event => {
    event.preventDefault()
    await api.subscribeNewsletter(email)
    setSubscribed(true)
    setEmail('')
  }
  return <footer className="footer"><div className="footer-top"><a className="brand footer-brand" href="/"><span className="brand-mark">RTS</span><span>PUBLICATION<small>{(settings?.tagline || 'YOUR STORY. OUR CRAFT.').toUpperCase()}</small></span></a><div><p className="footer-kicker">Have a story in you?</p><Button href="/publish">Let's bring it out</Button></div></div><div className="footer-columns"><div><h4>Company</h4><a href="/about">About us</a><a href="/contact">Contact</a><a href="/faq">FAQ</a><a href="/blog">Journal</a></div><div><h4>Publishing</h4><a href="/publish">Publish your book</a><a href="/packages">Packages</a><a href="/services">Services</a><a href="/authors">Authors</a></div><div><h4>Books</h4><a href="/books">All books</a><a href="/categories/fiction">Categories</a><a href="/books?sort=new">New releases</a><a href="/books?sort=bestseller">Bestsellers</a></div><div><h4>Support</h4><a href="/support">Help centre</a><a href="/shipping">Shipping & returns</a><a href="/privacy-policy">Privacy</a><a href="/terms">Terms</a></div><div className="newsletter"><h4>Stay in the story</h4><p>New books, author notes and publishing ideas in your inbox.</p>{subscribed ? <p>Thank you, you're subscribed.</p> : <form onSubmit={subscribe}><input type="email" required value={email} onChange={e => setEmail(e.target.value)} placeholder="Your email address" /><button aria-label="Subscribe"><ArrowRight size={17} /></button></form>}<div className="socials"><a href={settings?.socials?.instagram || '/instagram'}>Instagram</a><a href={settings?.socials?.facebook || '/facebook'}>Facebook</a><a href={settings?.socials?.linkedin || '/linkedin'}>LinkedIn</a></div></div></div><div className="footer-bottom"><span>© {new Date().getFullYear()} {settings?.siteName || 'RTS Publication'}. All rights reserved.</span><span>Made for the storytellers.</span><span>Visa · UPI · Mastercard</span></div></footer>
}

function Layout({ children, cartCount }) {
  return <><SiteHeader cartCount={cartCount} />{children}<Footer /></>
}

function BookCard({ book, onAdd, onWish, wished }) {
  return <article className="book-card-new"><div className="book-cover" style={{ background: book.color }}><img src={img(book)} alt={`${book.title} cover`} loading="lazy" /><button className={wished ? 'wish active' : 'wish'} onClick={() => onWish(book.id)} aria-label="Add to wishlist"><Heart size={17} fill={wished ? 'currentColor' : 'none'} /></button>{book.bestseller && <span className="badge">Bestseller</span>}</div><div className="rating"><Star size={13} fill="currentColor" /> {book.rating} <span>({book.reviewCount})</span></div><h3><a href={`/books/${book.slug}`}>{book.title}</a></h3><p>by {book.author}</p><div className="card-buy"><strong>₹{book.price.toLocaleString('en-IN')}</strong><del>₹{book.originalPrice.toLocaleString('en-IN')}</del><button onClick={() => onAdd(book)} aria-label={`Add ${book.title} to cart`}><ShoppingBag size={17} /></button></div></article>
}

function SectionHeading({ eyebrow, title, link, href }) {
  return <div className="section-heading-new"><div><span className="eyebrow">{eyebrow}</span><h2>{title}</h2></div><a className="round-link" href={href}>{link}<ArrowRight size={17} /></a></div>
}

function Home({ cart, setCart }) {
  const { settings } = useSettings()
  const { data: books } = useBooks({ sort: 'featured' })
  const { data: packages } = usePackages()
  const { data: authors } = useAuthors()
  const { data: posts } = usePosts()
  const add = book => setCart([...cart, { ...book, quantity: 1 }])
  return <Layout cartCount={cart.reduce((sum, item) => sum + item.quantity, 0)}><main><section className="home-hero"><div className="hero-content"><span className="eyebrow">{settings?.hero?.eyebrow || 'A HOME FOR NEW VOICES'}</span><h1>{settings?.hero?.title || 'Turn your manuscript into a published book.'}</h1><p>{settings?.hero?.copy}</p><div className="hero-actions"><Button href="/publish">Publish your book</Button><Button href="/books" secondary>Explore books</Button></div><div className="hero-trust"><strong>2,000+</strong><span>authors have trusted their words with us</span></div></div><div className="hero-visual"><div className="sun-shape" /><div className="hero-book"><span>RTS<br /><b>PUBLICATION</b></span></div><div className="hero-note">Your story<br /><i>starts here.</i></div></div></section><section className="benefits"><div><BookOpen /><strong>Editorial care</strong><span>Words that read beautifully.</span></div><div><span className="benefit-icon">✦</span><strong>Full ownership</strong><span>Your rights, your story.</span></div><div><ArrowRight /><strong>Global reach</strong><span>Readers everywhere.</span></div><div><User /><strong>Author support</strong><span>A team by your side.</span></div></section><section className="home-section"><SectionHeading eyebrow="FRESH OFF THE PRESS" title={<>Stories worth <em>discovering.</em></>} link="View all books" href="/books" /><div className="book-grid-new">{books.slice(0, 4).map(book => <BookCard key={book.id} book={book} onAdd={add} onWish={() => {}} />)}</div></section><section className="home-packages"><div><span className="eyebrow">A PATH FOR EVERY STORY</span><h2>Publish with <em>purpose.</em></h2><p>Whether you are publishing your first poem or your tenth research book, choose the support that fits your ambition.</p><Button href="/packages" secondary>Compare packages</Button></div><div className="package-preview">{packages.map((item, i) => <a href="/packages" className={`package-mini ${item.tone}`} key={item.slug}><span>0{i + 1}</span><strong>{item.name}</strong><b>{item.price}</b><small>{item.popular ? 'Most chosen' : 'View package'} ↗</small></a>)}</div></section><section className="home-section authors-preview"><SectionHeading eyebrow="THE VOICES WE AMPLIFY" title={<>Strong minds <em>behind us.</em></>} link="Meet our authors" href="/authors" /><div className="author-grid-new">{authors.slice(0, 5).map(author => <article key={author.id}><div className="author-photo"><img src={author.image} alt={author.name} loading="lazy" /></div><h3>{author.name}</h3><p>{author.role}</p></article>)}</div></section><section className="home-stats"><div><strong>{settings?.stats?.communityMembers}</strong><span>Community members</span></div><div><strong>{settings?.stats?.registeredWriters}</strong><span>Registered writers</span></div><div><strong>{settings?.stats?.booksPublished}</strong><span>Books published</span></div><div><strong>{settings?.stats?.countriesReached}</strong><span>Countries reached</span></div></section><section className="home-testimonial"><span>"</span><blockquote>{settings?.testimonial?.quote}</blockquote><small>— {settings?.testimonial?.author}</small></section><section className="home-journal"><SectionHeading eyebrow="FROM THE RTS JOURNAL" title={<>Notes for <em>storytellers.</em></>} link="Read the journal" href="/blog" /><div className="post-grid">{posts.map(post => <a className="post-card" href={`/blog/${post.slug}`} key={post.id}><div style={{ background: post.color }}><BookOpen size={28} /></div><small>{post.category} · {post.time}</small><h3>{post.title}</h3><span>Read article <ArrowRight size={14} /></span></a>)}</div></section><section className="home-cta"><span className="eyebrow">YOUR NEXT CHAPTER</span><h2>Ready to see your name<br />on a <em>cover?</em></h2><Button href="/publish">Start your publishing journey</Button></section></main></Layout>
}

function PageIntro({ eyebrow, title, copy }) {
  return <section className="page-intro"><span className="eyebrow">{eyebrow}</span><h1>{title}</h1><p>{copy}</p></section>
}

function EmptyState({ title, copy }) {
  return <div className="empty-state"><BookOpen size={36} /><h2>{title}</h2><p>{copy}</p><Button href="/books">Browse all books</Button></div>
}

function CartPage({ cart, setCart }) {
  const update = (id, amount) => setCart(cart.map(i => i.id === id ? { ...i, quantity: Math.max(1, i.quantity + amount) } : i))
  const remove = id => setCart(cart.filter(i => i.id !== id))
  const subtotal = cart.reduce((s, i) => s + i.price * i.quantity, 0)
  return <Layout cartCount={cart.reduce((s, i) => s + i.quantity, 0)}><main className="cart-page"><PageIntro eyebrow="YOUR RTS CART" title="Books selected with care." copy="Review your titles before you continue to checkout." />{cart.length ? <div className="cart-layout"><div className="cart-items">{cart.map(item => <div className="cart-item" key={item.id}><img src={img(item)} alt="" /><div><h3>{item.title}</h3><p>by {item.author}</p><strong>₹{item.price.toLocaleString('en-IN')}</strong></div><div className="quantity"><button onClick={() => update(item.id, -1)} aria-label="Decrease quantity"><Minus size={15} /></button><span>{item.quantity}</span><button onClick={() => update(item.id, 1)} aria-label="Increase quantity"><Plus size={15} /></button></div><button className="remove" onClick={() => remove(item.id)}>Remove</button></div>)}</div><aside className="order-summary"><span className="eyebrow">ORDER SUMMARY</span><div><span>Subtotal</span><strong>₹{subtotal.toLocaleString('en-IN')}</strong></div><div><span>Shipping</span><strong>{subtotal >= 999 ? 'Free' : '₹99'}</strong></div><hr /><div className="total"><span>Total</span><strong>₹{(subtotal + (subtotal >= 999 ? 0 : 99)).toLocaleString('en-IN')}</strong></div><Button href="/checkout">Proceed to checkout</Button><a href="/books" className="continue">Continue shopping</a></aside></div> : <EmptyState title="Your cart is empty." copy="The next story is waiting to be found." />}</main></Layout>
}

function DetailPage({ type, slug, cart, setCart }) {
  const bookLookup = useBook(type === 'book' ? slug : null)
  const authorLookup = useAuthor(type === 'author' ? slug : null)
  if (type === 'author') {
    const author = authorLookup.author
    if (!author) return <Layout cartCount={cart.reduce((s, i) => s + i.quantity, 0)}><main className="detail-page" /></Layout>
    return <Layout cartCount={cart.reduce((s, i) => s + i.quantity, 0)}><main className="detail-page"><PageIntro eyebrow="RTS AUTHOR" title={author.name} copy={`${author.role} · ${author.genre}`} /><section className="author-detail"><img src={author.image} alt={author.name} /><div><span className="eyebrow">ABOUT THE AUTHOR</span><h2>A voice with something <em>worth saying.</em></h2><p>{author.bio} RTS Publication is proud to help this work find its readers.</p><div className="author-facts"><span><b>{author.books}</b> Books</span><span><b>{author.genre}</b> Genre</span></div><Button href="/books">Explore their books</Button></div></section></main></Layout>
  }
  const item = bookLookup.book
  if (!item) return <Layout cartCount={cart.reduce((s, i) => s + i.quantity, 0)}><main className="detail-page" /></Layout>
  return <Layout cartCount={cart.reduce((s, i) => s + i.quantity, 0)}><main className="detail-page"><section className="product-detail"><div className="product-cover" style={{ background: item.color }}><img src={img(item)} alt={`${item.title} cover`} /></div><div className="product-info"><span className="eyebrow">{item.category.toUpperCase()} · RTS EDITION</span><h1>{item.title}</h1><p className="byline">by <a href={`/authors/${item.authorSlug}`}>{item.author}</a></p><div className="rating"><Star size={14} fill="currentColor" /> {item.rating} · {item.reviewCount} reviews</div><p className="product-description">{item.description}</p><div className="product-price">₹{item.price.toLocaleString('en-IN')} <del>₹{item.originalPrice.toLocaleString('en-IN')}</del></div><div className="format-row"><label>Format<select><option>Paperback</option><option>E-book</option></select></label><Button onClick={() => setCart([...cart, { ...item, quantity: 1 }])}>Add to cart</Button></div><div className="specs"><span><b>ISBN</b>{item.isbn}</span><span><b>Pages</b>{item.pages}</span><span><b>Language</b>{item.language}</span><span><b>Published</b>2024</span></div></div></section><section className="product-lower"><div><span className="eyebrow">ABOUT THIS BOOK</span><h2>A story made to <em>stay.</em></h2><p>{item.description} It is a book for readers who want generous ideas, honest language and a little more room to think.</p></div><div className="review-box"><Star size={17} fill="currentColor" /><Star size={17} fill="currentColor" /><Star size={17} fill="currentColor" /><Star size={17} fill="currentColor" /><Star size={17} fill="currentColor" /><p>"A beautiful addition to any thoughtful reader's shelf."</p><small>Verified reader</small></div></section></main></Layout>
}

function StandardPage({ route }) {
  const { settings } = useSettings()
  return <Layout><main className="standard-page"><PageIntro eyebrow={(settings?.siteName || 'RTS PUBLICATION').toUpperCase()} title="Your story deserves a shelf." copy={settings?.hero?.copy || 'A warm, considered publishing experience for authors ready to share their work.'} /><div className="standard-content"><h2>Publishing with <em>purpose.</em></h2><p>Our team stays close, keeps the process transparent and gives every book the time it deserves.</p><Button href="/publish">Start your journey</Button></div></main></Layout>
}

function SearchPage({ cart, setCart }) {
  const query = new URLSearchParams(window.location.search).get('q') || ''
  const { books } = useSearch(query)
  return <Layout cartCount={cart.reduce((s, i) => s + i.quantity, 0)}><main className="catalog-page"><PageIntro eyebrow="SEARCH RTS" title={query ? `Results for "${query}"` : 'What are you looking for?'} copy="Search books, authors and categories across RTS Publication." /><div className="search-results">{books.length ? <div className="book-grid-new">{books.map(b => <BookCard key={b.id} book={b} onAdd={book => setCart([...cart, { ...book, quantity: 1 }])} onWish={() => {}} />)}</div> : <EmptyState title="No search results." copy="Try searching for a book title, author or genre." />}</div></main></Layout>
}

function ContactPage() {
  const { settings } = useSettings()
  const [sent, setSent] = useState(false)
  const [loading, setLoading] = useState(false)
  const submit = async event => {
    event.preventDefault()
    setLoading(true)
    const form = new FormData(event.currentTarget)
    await api.submitContact({ name: form.get('name'), email: form.get('email'), phone: form.get('phone'), subject: form.get('subject'), message: form.get('message') })
    setLoading(false)
    setSent(true)
  }
  return <Layout><main className="standard-page"><PageIntro eyebrow="GET IN TOUCH" title="Let us hear your story." copy="Our publishing advisors are ready to answer questions about manuscripts, packages and the next step." />{sent ? <div className="success-state"><Check size={44} /><h2>Message received.</h2><p>Thank you. We will get back to you within one working day.</p><Button href="/">Back to home</Button></div> : <section className="contact-layout"><form className="contact-form" onSubmit={submit}><h2>Send an enquiry.</h2><div className="form-grid"><input required name="name" placeholder="Your name" /><input required type="email" name="email" placeholder="Email address" /><input required name="phone" placeholder="Phone number" /><input required name="subject" placeholder="Subject" /></div><textarea required name="message" rows="6" placeholder="How can we help?"></textarea><button className="button" type="submit" disabled={loading}>{loading ? 'Sending...' : 'Send message'} <ArrowRight size={16} /></button></form><aside className="contact-details"><span className="eyebrow">{(settings?.siteName || 'RTS PUBLICATION').toUpperCase()}</span><h2>Good conversations<br /><em>start here.</em></h2><p>{settings?.contact?.address} · {settings?.contact?.hours}</p><a href={`mailto:${settings?.contact?.email}`}>{settings?.contact?.email}</a><a href={`tel:${settings?.contact?.phone}`}>{settings?.contact?.phone}</a><div className="map-placeholder">RTS STUDIO<br /><small>{settings?.contact?.address?.toUpperCase()}</small></div></aside></section>}</main></Layout>
}

function FaqPage() {
  const { data: faqs } = useFaqs()
  const [active, setActive] = useState(0)
  return <Layout><main className="standard-page"><PageIntro eyebrow="FREQUENTLY ASKED QUESTIONS" title="A little clarity." copy="Find quick answers about timelines, rights, languages, services and publishing with RTS." /><div className="faq-page-list">{faqs.map((faq, i) => <div className={active === i ? 'faq-row active' : 'faq-row'} key={faq.id}><button onClick={() => setActive(active === i ? -1 : i)}>{faq.question}<span>{active === i ? '−' : '+'}</span></button>{active === i && <p>{faq.answer}</p>}</div>)}</div></main></Layout>
}

function BlogDetail({ slug }) {
  const { post } = usePost(slug)
  if (!post) return <Layout><main className="article-page" /></Layout>
  return <Layout><main className="article-page"><PageIntro eyebrow={`RTS JOURNAL · ${(post.category || '').toUpperCase()}`} title={post.title} copy={`${post.date} · ${post.time}`} /><article className="article-body"><div className="article-art" style={{ background: post.color }}><BookOpen size={52} /></div>{(post.body || '').split('\n\n').map((paragraph, i) => <p key={i}>{paragraph}</p>)}<Button href="/publish">Talk to a publishing advisor</Button></article></main></Layout>
}

function CheckoutPage({ cart, setCart }) {
  const [done, setDone] = useState(false)
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState(null)
  const subtotal = cart.reduce((s, i) => s + i.price * i.quantity, 0)
  const submit = async event => {
    event.preventDefault()
    setLoading(true)
    setError(null)
    const form = new FormData(event.currentTarget)
    try {
      await api.placeOrder({
        name: form.get('name'), email: form.get('email'), phone: form.get('phone'), company: form.get('company'),
        address: form.get('address'), city: form.get('city'), state: form.get('state'), pin_code: form.get('pin_code'),
        payment_method: 'mock',
        items: cart.map(item => ({ slug: item.slug, quantity: item.quantity })),
      })
      setCart([])
      setDone(true)
    } catch (err) {
      setError(err.message || 'Something went wrong. Please try again.')
    } finally {
      setLoading(false)
    }
  }
  if (done) return <Layout><main className="success-state"><Check size={44} /><h2>Order received.</h2><p>This is a mock checkout. No payment was processed.</p><Button href="/books">Continue shopping</Button></main></Layout>
  return <Layout cartCount={cart.reduce((s, i) => s + i.quantity, 0)}><main className="checkout-page"><PageIntro eyebrow="SECURE CHECKOUT" title="Your next chapter is close." copy="Complete your details. Payment integration can be connected here later." /><form className="checkout-layout" onSubmit={submit}><section className="checkout-form"><h2>Customer details</h2><div className="form-grid"><input required name="name" placeholder="Full name" /><input required type="email" name="email" placeholder="Email address" /><input required name="phone" placeholder="Phone number" /><input name="company" placeholder="Company (optional)" /></div><h2>Shipping address</h2><div className="form-grid"><input required name="address" placeholder="Address" /><input required name="city" placeholder="City" /><input required name="state" placeholder="State" /><input required name="pin_code" placeholder="PIN code" /></div><h2>Payment method</h2><label className="payment-choice"><input type="radio" name="payment" defaultChecked /> Mock payment / Pay later <span>Razorpay-ready</span></label>{error && <p className="form-error">{error}</p>}<button className="button" type="submit" disabled={loading}>{loading ? 'Placing order...' : 'Place mock order'} <ArrowRight size={16} /></button></section><aside className="order-summary"><span className="eyebrow">ORDER SUMMARY</span>{cart.map(item => <div key={item.id}><span>{item.title} × {item.quantity}</span><strong>₹{(item.price * item.quantity).toLocaleString('en-IN')}</strong></div>)}<hr /><div className="total"><span>Total</span><strong>₹{subtotal.toLocaleString('en-IN')}</strong></div></aside></form></main></Layout>
}

export { Layout, BookCard, PageIntro, EmptyState, Home, CartPage, DetailPage, StandardPage, SearchPage, ContactPage, FaqPage, BlogDetail, CheckoutPage }
