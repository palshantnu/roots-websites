import { ArrowRight, Check } from 'lucide-react'
import { usePackagePage } from '../hooks'

export default function PackageDetailPage({ Layout, PageIntro, slug }) {
  const { page } = usePackagePage(slug)
  if (!page) return <Layout><main className="standard-page" /></Layout>
  return <Layout><main className="standard-page"><PageIntro eyebrow={page.eyebrow || 'RTS PACKAGE DETAIL'} title={page.heading} copy={page.copy} /><section className="service-detail"><div><span className="eyebrow">WHAT IS INCLUDED</span><h2>Make your book <em>travel.</em></h2><p>{page.copy} Our publishing advisors will help you select the right combination of formats, production and distribution for your goals.</p><a className="button" href="/publish">Request package details <ArrowRight size={16} /></a></div><div className="service-benefits">{(page.benefits || []).map(item => <div key={item}><Check size={16} />{item}</div>)}</div></section></main></Layout>
}
