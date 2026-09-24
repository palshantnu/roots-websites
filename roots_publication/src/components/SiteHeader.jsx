import { ChevronDown, Menu, Search, ShoppingBag, User, X } from 'lucide-react'
import { useState } from 'react'

const links = [
  { label: 'Books', href: '/books', items: [['All books', '/books'], ['Categories', '/categories'], ['New releases', '/books?sort=new'], ['Bestsellers', '/books?sort=bestseller']] },
  { label: 'Authors', href: '/authors' },
  { label: 'Services', href: '/services', items: [['All services', '/services'], ['Editing & proofreading', '/services/editing-proofreading'], ['Cover design', '/services/cover-design'], ['Distribution', '/services/distribution'], ['Marketing & publicity', '/services/marketing-publicity']] },
  { label: 'Packages', href: '/packages', items: [['Compare packages', '/packages'], ['Paperback', '/paperback-packages'], ['E-book', '/ebook-packages'], ['Children books', '/childrenbook-packages'], ['International publishing', '/self-publishing-usa']] },
  { label: 'About', href: '/about' },
  { label: 'Contact', href: '/contact' },
]

export default function SiteHeader({ cartCount = 0 }) {
  const [open, setOpen] = useState(false)
  const [searchOpen, setSearchOpen] = useState(false)
  const [dropdown, setDropdown] = useState(null)
  return <>
  {/* <div className="announcement">
    Free shipping on orders over ₹999 <span>•</span> Publishing consultations available Mon–Sat
    </div> */}
    <header className="header"><a className="brand" href="/"><span className="brand-mark">RTS</span><span>PUBLICATION<small>YOUR STORY. OUR CRAFT.</small></span></a><nav className={open ? 'main-nav open' : 'main-nav'}>{links.map(link => link.items ? <div className="nav-dropdown" key={link.label}><a href={link.href} onClick={() => setOpen(false)}>{link.label}<ChevronDown size={13} /></a><div className={dropdown === link.label ? 'dropdown-menu visible' : 'dropdown-menu'} onMouseEnter={() => setDropdown(link.label)}>{link.items.map(([label, href]) => <a href={href} key={label} onClick={() => { setOpen(false); setDropdown(null) }}>{label}</a>)}</div><button className="dropdown-trigger" aria-label={`Open ${link.label} menu`} onClick={() => setDropdown(dropdown === link.label ? null : link.label)}><ChevronDown size={14} /></button></div> : <a href={link.href} key={link.label} onClick={() => setOpen(false)}>{link.label}</a>)}<a href="/search" onClick={() => setOpen(false)}><Search size={18} /> Search</a><a href="/account" onClick={() => setOpen(false)}><User size={18} /> Account</a><a className="cart-link" href="/cart" onClick={() => setOpen(false)}><ShoppingBag size={19} /><span>{cartCount}</span></a><a className="button" href="/publish" onClick={() => setOpen(false)}>Publish your book</a></nav><button className="mobile-search" onClick={() => setSearchOpen(!searchOpen)} aria-label="Open search"><Search size={21} /></button><button className="menu-button" onClick={() => setOpen(!open)} aria-label="Open menu">{open ? <X /> : <Menu />}</button></header>{searchOpen && <form className="quick-search" onSubmit={event => { event.preventDefault(); window.location.href = `/search?q=${event.currentTarget.elements.q.value}` }}><Search size={18} /><input name="q" autoFocus placeholder="Search books, authors, topics..." /><button type="button" aria-label="Close search" onClick={() => setSearchOpen(false)}><X size={18} /></button></form>}</>
}
