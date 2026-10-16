import { ArrowRight, Check } from 'lucide-react'

const thesisTypes = [
  { title: 'PhD thesis', copy: 'Doctoral theses across sciences, engineering, management, humanities and social sciences, from structuring chapters to the final bound copy.' },
  { title: 'M.Phil dissertation', copy: 'Focused dissertations with a clear research question, solid literature review and well-argued findings.' },
  { title: "Master's thesis", copy: 'M.Tech, M.E., MBA, M.Sc., M.A., M.Com and MCA theses that meet your university format and guidelines.' },
  { title: "Bachelor's project report", copy: 'B.Tech, BBA, BCA and B.Sc. final-year project reports and dissertations, written clearly and formatted correctly.' },
  { title: 'Medical & nursing thesis', copy: 'MD, MS, DNB, MDS and M.Sc. Nursing theses, including statistics, tables and referencing support.' },
  { title: 'Law & management thesis', copy: 'LL.M. dissertations and management research with case studies, surveys and data-backed analysis.' },
]

const synopsisPoints = ['Title and research problem', 'Objectives and hypotheses', 'Review of literature', 'Research methodology', 'Expected outcomes and timeline', 'References in your required style']

const paperPoints = ['Topic selection and structuring', 'Writing, editing and proofreading', 'Plagiarism check and report', 'Journal selection (Scopus, UGC Care, SCI, Web of Science)', 'Formatting to journal guidelines', 'Submission and reviewer-response support']

const process = [
  ['Consultation', 'Share your topic, university guidelines and deadline with our research advisor.'],
  ['Planning', 'We agree on scope, chapter outline, methodology and a clear timeline.'],
  ['Writing & analysis', 'Subject experts draft chapters and run the data analysis (SPSS, R, Python, MATLAB).'],
  ['Review & revisions', 'You review every chapter; we revise until it reads the way you want.'],
  ['Final delivery', 'Plagiarism report, formatting, printing and binding, ready to submit.'],
]

export default function ThesisPage({ Layout, PageIntro }) {
  return <Layout><main className="standard-page thesis-page">
    <PageIntro eyebrow="THESIS & RESEARCH SUPPORT" title="Research that reads as well as it argues." copy="From the first synopsis to the final bound thesis and published research paper, RTS gives scholars expert guidance at every step." />

    <section id="types">
      <div className="thesis-heading"><span className="eyebrow">THESIS WE WORK ON</span><h2>Every level, every <em>discipline.</em></h2></div>
      <div className="services-grid">{thesisTypes.map((item, index) => <article className="service-card" key={item.title}><span>0{index + 1}</span><h2>{item.title}</h2><p>{item.copy}</p></article>)}</div>
    </section>

    <section id="synopsis" className="service-detail">
      <div><span className="eyebrow">SYNOPSIS</span><h2>A strong start for your <em>research.</em></h2><p>Your synopsis is the first thing your committee reads. We help you shape a focused, well-justified proposal that clearly states what you will study, why it matters and how you will do it, so approval comes faster.</p><a className="button" href="/contact">Discuss your synopsis <ArrowRight size={16} /></a></div>
      <div className="service-benefits">{synopsisPoints.map(item => <div key={item}><Check size={16} />{item}</div>)}</div>
    </section>

    <section id="research-paper" className="service-detail thesis-paper">
      <div><span className="eyebrow">RESEARCH PAPER</span><h2>From manuscript to <em>publication.</em></h2><p>Turn your research into a paper that journals accept. We help with writing, editing and formatting, choose the right indexed journal for your work and support you through peer review until your paper is published.</p><a className="button" href="/contact">Publish your paper <ArrowRight size={16} /></a></div>
      <div className="service-benefits">{paperPoints.map(item => <div key={item}><Check size={16} />{item}</div>)}</div>
    </section>

    <section className="thesis-process">
      <div className="thesis-heading"><span className="eyebrow">HOW WE WORK</span><h2>A clear path to <em>submission.</em></h2></div>
      <ol>{process.map(([title, copy], index) => <li key={title}><b>0{index + 1}</b><strong>{title}</strong><p>{copy}</p></li>)}</ol>
    </section>

    <section className="home-cta"><span className="eyebrow">START YOUR RESEARCH</span><h2>Ready to complete your<br /><em>thesis?</em></h2><a className="button" href="/contact">Talk to a research advisor <ArrowRight size={16} /></a></section>
  </main></Layout>
}
