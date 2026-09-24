import SEO from '../components/common/SEO';
import PageHero from '../components/templates/PageHero';
import Reveal from '../components/common/Reveal';
import SectionTitle from '../components/common/SectionTitle';
import Badge from '../components/common/Badge';
import CTASection from '../components/sections/CTASection';
import { caseStudies } from '../data/caseStudies';

export default function CaseStudies() {
  return (
    <>
      <SEO
        title="Case Studies"
        description="In-depth case studies from Roots Technology — the client challenge, our solution, the technologies used and the measurable results."
      />

      <PageHero
        eyebrow="Case Studies"
        title="The problem, the approach, the numbers"
        subtitle="A closer look at how specific engagements moved specific metrics. Figures are representative placeholder data."
        trail={[{ label: 'Case Studies' }]}
        actions={[{ label: 'Discuss Your Project', to: '/contact' }]}
      />

      <section className="section">
        <div className="container stack" style={{ display: 'grid', gap: 'clamp(28px,4vw,44px)' }}>
          {caseStudies.map((cs, i) => (
            <Reveal key={cs.id} delay={0.04}>
              <article className="card" style={{ padding: 0, overflow: 'hidden' }}>
                <div className={`split ${i % 2 ? 'split--reverse' : ''}`} style={{ gap: 0, alignItems: 'stretch' }}>
                  <div className="split__media" style={{ borderRadius: 0, boxShadow: 'none', aspectRatio: 'auto' }}>
                    <img src={cs.image} alt={cs.client} loading="lazy" style={{ height: '100%' }} />
                  </div>
                  <div style={{ padding: 'clamp(24px,4vw,40px)', display: 'grid', gap: 14, alignContent: 'center' }}>
                    <div className="chip-row">
                      <Badge>{cs.industry}</Badge>
                      <Badge variant="soft">{cs.client}</Badge>
                    </div>
                    <h3 style={{ fontSize: '1.4rem' }}>{cs.title}</h3>

                    <div>
                      <p style={{ fontFamily: 'var(--font-heading)', fontWeight: 700, color: 'var(--text-strong)' }}>
                        Challenge
                      </p>
                      <p>{cs.challenge}</p>
                    </div>
                    <div>
                      <p style={{ fontFamily: 'var(--font-heading)', fontWeight: 700, color: 'var(--text-strong)' }}>
                        Our solution
                      </p>
                      <p>{cs.solution}</p>
                    </div>

                    <div className="chip-row">
                      {cs.technologies.map((t) => (
                        <span key={t} className="badge badge--soft">
                          {t}
                        </span>
                      ))}
                    </div>

                    <div className="grid grid-3" style={{ gap: 14, marginTop: 6 }}>
                      {cs.results.map((r) => (
                        <div key={r.label} className="stat" style={{ padding: '18px 12px' }}>
                          <div className="stat__value" style={{ fontSize: '1.7rem' }}>
                            {r.value}
                          </div>
                          <p className="stat__label" style={{ fontSize: '0.82rem' }}>
                            {r.label}
                          </p>
                        </div>
                      ))}
                    </div>
                  </div>
                </div>
              </article>
            </Reveal>
          ))}
        </div>
      </section>

      <section className="section section--muted">
        <div className="container">
          <SectionTitle eyebrow="Metrics we move" title="What success has looked like" align="center" />
          <div className="grid grid-3">
            {[
              { value: '200%+', label: 'Organic traffic growth on growth programmes' },
              { value: '150%', label: 'Typical increase in qualified leads' },
              { value: '40%', label: 'Average performance improvement on rebuilds' },
            ].map((m) => (
              <Reveal key={m.label}>
                <div className="stat">
                  <div className="stat__value">{m.value}</div>
                  <p className="stat__label">{m.label}</p>
                </div>
              </Reveal>
            ))}
          </div>
        </div>
      </section>

      <CTASection
        title="Want results like these?"
        primary={{ label: 'Get a Free Consultation', to: '/contact' }}
        secondary={{ label: 'See the Portfolio', to: '/portfolio' }}
      />
    </>
  );
}
