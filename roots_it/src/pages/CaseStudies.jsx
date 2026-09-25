import SEO from '../components/common/SEO';
import PageHero from '../components/templates/PageHero';
import Reveal from '../components/common/Reveal';
import SectionTitle from '../components/common/SectionTitle';
import Badge from '../components/common/Badge';
import AsyncState from '../components/common/AsyncState';
import SmartImage from '../components/common/SmartImage';
import CTASection from '../components/sections/CTASection';
import { useCaseStudies, usePage, useSection } from '../hooks/useApi';

export default function CaseStudies() {
  const { page } = usePage('case-studies');
  const { data: caseStudies, loading, error, reload } = useCaseStudies();
  const { items: metrics } = useSection('case_study_metrics');

  return (
    <>
      <SEO page={page} />

      <PageHero
        page={page}
        trail={[{ label: 'Case Studies' }]}
        actions={[{ label: 'Discuss Your Project', to: '/contact' }]}
      />

      <section className="section">
        <div className="container stack" style={{ display: 'grid', gap: 'clamp(28px,4vw,44px)' }}>
          <AsyncState
            loading={loading}
            error={error}
            empty={caseStudies.length === 0}
            onRetry={reload}
            emptyText="Case studies will be published here soon."
          >
            {caseStudies.map((cs, i) => (
              <Reveal key={cs.id} delay={0.04}>
                <article className="card" style={{ padding: 0, overflow: 'hidden' }}>
                  <div className={`split ${i % 2 ? 'split--reverse' : ''}`} style={{ gap: 0, alignItems: 'stretch' }}>
                    <div className="split__media" style={{ borderRadius: 0, boxShadow: 'none', aspectRatio: 'auto' }}>
                      <SmartImage src={cs.image} alt={cs.client ?? cs.title} loading="lazy" style={{ height: '100%' }} />
                    </div>
                    <div style={{ padding: 'clamp(24px,4vw,40px)', display: 'grid', gap: 14, alignContent: 'center' }}>
                      <div className="chip-row">
                        {cs.industry && <Badge>{cs.industry}</Badge>}
                        {cs.client && <Badge variant="soft">{cs.client}</Badge>}
                      </div>
                      <h3 style={{ fontSize: '1.4rem' }}>{cs.title}</h3>

                      {cs.challenge && (
                        <div>
                          <p style={{ fontFamily: 'var(--font-heading)', fontWeight: 700, color: 'var(--text-strong)' }}>
                            Challenge
                          </p>
                          <p>{cs.challenge}</p>
                        </div>
                      )}
                      {cs.solution && (
                        <div>
                          <p style={{ fontFamily: 'var(--font-heading)', fontWeight: 700, color: 'var(--text-strong)' }}>
                            Our solution
                          </p>
                          <p>{cs.solution}</p>
                        </div>
                      )}

                      <div className="chip-row">
                        {cs.technologies.map((t) => (
                          <span key={t} className="badge badge--soft">
                            {t}
                          </span>
                        ))}
                      </div>

                      {cs.results.length > 0 && (
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
                      )}
                    </div>
                  </div>
                </article>
              </Reveal>
            ))}
          </AsyncState>
        </div>
      </section>

      {metrics.length > 0 && (
        <section className="section section--muted">
          <div className="container">
            <SectionTitle eyebrow="Metrics we move" title="What success has looked like" align="center" />
            <div className="grid grid-3">
              {metrics.map((m) => (
                <Reveal key={m.id}>
                  <div className="stat">
                    <div className="stat__value">{m.value}</div>
                    <p className="stat__label">{m.title}</p>
                  </div>
                </Reveal>
              ))}
            </div>
          </div>
        </section>
      )}

      <CTASection
        cta={page?.cta}
        primary={{ label: 'Get a Free Consultation', to: '/contact' }}
        secondary={{ label: 'See the Portfolio', to: '/portfolio' }}
      />
    </>
  );
}
