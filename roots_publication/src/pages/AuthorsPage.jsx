import { ArrowRight } from 'lucide-react'
import { useAuthors } from '../hooks'
export default function AuthorsPage({ Layout, PageIntro }) {
  const { data: authors } = useAuthors()
  return <Layout><main className="catalog-page"><PageIntro eyebrow="THE RTS AUTHOR COMMUNITY" title="Strong minds behind every page." copy="Meet the writers, researchers, poets and thinkers whose work has found a home with RTS Publication." /><div className="author-grid-new author-catalog">{authors.map(author => <a href={`/authors/${author.slug}`} key={author.id}><div className="author-photo"><img src={author.image} alt={author.name} loading="lazy" /></div><h3>{author.name}</h3><p>{author.role} · {author.books} books <ArrowRight size={13} /></p></a>)}</div></main></Layout>
}
