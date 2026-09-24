import { useMemo } from 'react';
import { FiCheck } from 'react-icons/fi';
import SEO from '../common/SEO';
import Reveal from '../common/Reveal';
import SectionTitle from '../common/SectionTitle';
import FAQ from '../common/FAQ';
import { Icon, TechIcon } from '../../utils/iconMap';
import { getServiceDetail } from '../../data/serviceDetails';
import { technologyGroups } from '../../data/technologies';
import PageHero from './PageHero';
import ProcessTimeline from '../sections/ProcessTimeline';
import StatsSection from '../sections/StatsSection';
import CTASection from '../sections/CTASection';
import NotFound from '../../pages/NotFound';

/** Flatten the grouped technology list into a lookup by name (lowercase). */
function useTechLookup() {
  return useMemo(() => {
    const map = {};
    technologyGroups.forEach((g) =>
      g.items.forEach((t) => {
        map[t.name.toLowerCase()] = t.icon;
      })
    );
    return map;
  }, []);
}

/**
 * Renders a full service page from a `serviceDetails` entry.
 * Every /services/* and /digital-marketing/* page is just:
 *   <ServiceDetailTemplate slug="website-development" />
 */
export default function ServiceDetailTemplate({ slug }) {
  const data = getServiceDetail(slug);
  const techLookup = useTechLookup();

  if (!data) return <NotFound />;

  const {
    seoTitle,
    seoDescription,
    eyebrow,
    title,
    subtitle,
    intro = [],
    offerings = [],
    technologies = [],
    process = [],
    benefits = [],
    industries = [],
    faqs = [],
    stats,
    asideTitle,
    asidePoints = [],
    parent,
    cta,
  } = data;

  const trail = parent
    ? [parent, { label: eyebrow }]
    : [{ label: 'Services', to: '/services' }, { label: eyebrow }];

  return (
    <>
      <SEO title={seoTitle} description={seoDescription} type="article" />

      <PageHero
        eyebrow={eyebrow}
        title={title}
        subtitle={subtitle}
        trail={trail}
        actions={[
          { label: 'Get a Free Consultation', to: '/contact' },
          { label: 'View Our Work', to: '/portfolio', variant: 'ghost-light' },
        ]}
      />

      {/* Intro + aside */}
      <section className="section section--muted">
        <div className="container">
          <div className="svc-intro">
            <Reveal>
              <span className="eyebrow">Overview</span>
              <h2 style={{ margin: '14px 0 18px' }}>What we do</h2>
              {intro.map((p) => (
                <p key={p.slice(0, 24)} style={{ marginBottom: 14 }}>
                  {p}
                </p>
              ))}
            </Reveal>

            {asidePoints.length > 0 && (
              <Reveal className="svc-intro__aside" delay={0.1}>
                <h4>{asideTitle || 'What’s included'}</h4>
                <ul className="feature-list">
                  {asidePoints.map((point) => (
                    <li key={point}>
                      <FiCheck />
                      {point}
                    </li>
                  ))}
                </ul>
              </Reveal>
            )}
          </div>
        </div>
      </section>

      {/* Offerings */}
      {offerings.length > 0 && (
        <section className="section">
          <div className="container">
            <SectionTitle eyebrow="Capabilities" title={`${eyebrow} — what’s covered`} align="center">
              A complete offering, delivered by one senior team.
            </SectionTitle>
            <div className="grid grid-3">
              {offerings.map((o, i) => (
                <Reveal key={o.title} delay={(i % 3) * 0.06}>
                  <article className="card card--hover offer-card">
                    <span className="icon-tile" aria-hidden="true">
                      <Icon name={o.icon} />
                    </span>
                    <h3>{o.title}</h3>
                    <p>{o.description}</p>
                  </article>
                </Reveal>
              ))}
            </div>
          </div>
        </section>
      )}

      {/* Technologies */}
      {technologies.length > 0 && (
        <section className="section section--muted">
          <div className="container">
            <SectionTitle eyebrow="Stack" title="Technologies we use" align="center" />
            <Reveal className="tech-list" style={{ justifyContent: 'center' }}>
              {technologies.map((name) => (
                <span className="tech-pill" key={name}>
                  <TechIcon name={techLookup[name.toLowerCase()] || name.toLowerCase()} />
                  {name}
                </span>
              ))}
            </Reveal>
          </div>
        </section>
      )}

      {/* Process */}
      {process.length > 0 && (
        <section className="section section--dark">
          <div className="container">
            <SectionTitle eyebrow="Process" title="How we deliver" align="center">
              A predictable rhythm with visibility at every step.
            </SectionTitle>
            <div className="container--narrow" style={{ padding: 0, margin: '0 auto' }}>
              <ProcessTimeline steps={process} />
            </div>
          </div>
        </section>
      )}

      {/* Stats (optional) */}
      {stats && stats.length > 0 && (
        <StatsSection
          items={stats.map((s, i) => ({ id: i, ...s }))}
          eyebrow="Impact"
          title="What clients typically see"
        />
      )}

      {/* Benefits + Industries */}
      {(benefits.length > 0 || industries.length > 0) && (
        <section className="section">
          <div className="container">
            <div className="split">
              {benefits.length > 0 && (
                <Reveal>
                  <span className="eyebrow">Why Roots Technology</span>
                  <h2 style={{ margin: '14px 0 20px' }}>The difference in practice</h2>
                  <ul className="feature-list">
                    {benefits.map((b) => (
                      <li key={b}>
                        <FiCheck />
                        {b}
                      </li>
                    ))}
                  </ul>
                </Reveal>
              )}

              {industries.length > 0 && (
                <Reveal delay={0.1}>
                  <span className="eyebrow">Industries</span>
                  <h2 style={{ margin: '14px 0 20px' }}>Sectors we know well</h2>
                  <div className="pill-grid">
                    {industries.map((ind) => (
                      <span key={ind}>{ind}</span>
                    ))}
                  </div>
                </Reveal>
              )}
            </div>
          </div>
        </section>
      )}

      {/* FAQs */}
      {faqs.length > 0 && (
        <section className="section section--muted">
          <div className="container container--narrow">
            <SectionTitle eyebrow="FAQs" title="Questions, answered" align="center" />
            <FAQ items={faqs} defaultOpen={0} />
          </div>
        </section>
      )}

      <CTASection
        title={cta?.title || 'Let’s talk about your project'}
        primary={{ label: 'Start Your Project', to: '/contact' }}
        secondary={{ label: 'Request a Quote', to: '/contact' }}
      />
    </>
  );
}
