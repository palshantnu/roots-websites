import { ArrowRight } from 'lucide-react'
import { useCategories } from '../hooks'
export default function CategoriesPage({ Layout, PageIntro }) {
  const { categories } = useCategories()
  return <Layout><main className="standard-page"><PageIntro eyebrow="BOOK CATEGORIES" title="Find your kind of story." copy="Browse the RTS library by genre, subject and the questions that matter." /><div className="category-grid-new">{categories.map(category => <a href={`/books?category=${category}`} key={category}><span>{category}</span><ArrowRight size={17} /></a>)}</div></main></Layout>
}
