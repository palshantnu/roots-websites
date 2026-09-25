import { useMemo } from 'react';
import { FiCheck } from 'react-icons/fi';
import SEO from '../common/SEO';
import Reveal from '../common/Reveal';
import SectionTitle from '../common/SectionTitle';
import FAQ from '../common/FAQ';
import Loader from '../common/Loader';
import AsyncState from '../common/AsyncState';
import { Icon, TechIcon } from '../../utils/iconMap';
import { usePage, useSection } from '../../hooks/useApi';
import PageHero from './PageHero';
import ProcessTimeline from '../sections/ProcessTimeline';
import StatsSection from '../sections/StatsSection';
import CTASection from '../sections/CTASection';
import NotFound from '../../pages/NotFound';

/** Technology display names by logo key, from the admin technology list. */
function useTechNames() {
  const { items } = useSection('technologies');
  return useMemo(() => Object.fromEntries(items.map((t) => [t.value, t.title])), [items]);
}

/**
 * Renders a full service page from its admin page record (hero, SEO, CTA and
 * the `content` blocks). Every /services/* and /digital-marketing/* page is:
 *   <ServiceDetailTemplate slug="website-development" />
 */
export default function ServiceDetailTemplate({ slug }) {
  const { page, loading, error, reload } = usePage(slug);
  const techNames = useTechNames();

  if (!page) {
    if (loading) return <Loader />;
    if (error) {
      return (
        <section className="section">
          <div className="container container--narrow">
            <AsyncState error={error} empty onRetry={reload} errorText="We couldn’t load this page right now." />
          </div>
        </section>
      );
    }
    return <NotFound />;
  }

  const content = page.content ?? {};
  const {
    intro = [],
    offerings = [],
    technologies = [],
    process = [],
    benefits = [],
    industries = [],
    faqs = [],
    stats,
    aside_title: asideTitle,
    aside_points: asidePoints = [],
  } = content;

  const eyebrow = page.eyebrow;
  const trail = [
    { label: content.parent_label || 'Services', to: content.parent_link || '/services' },
    { label: eyebrow },
  ];

  return (
    <>
      <SEO page={page} type="article" />

      <PageHero
        page={page}
        trail={trail}
        actions={[
          { label: 'Get a Free Consultation', to: '/contact' },
          { label: 'View Our Work', to: '/portfolio', variant: 'ghost-light' },
        ]}
      />

      {/* Intro + aside */}
      {(intro.length > 0 || asidePoints.length > 0) && (
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
      )}

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
              {technologies.map((key) => (
                <span className="tech-pill" key={key}>
                  <TechIcon name={key} />
                  {techNames[key] || key}
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
        cta={page.cta}
        title="Let’s talk about your project"
        primary={{ label: 'Start Your Project', to: '/contact' }}
        secondary={{ label: 'Request a Quote', to: '/contact' }}
      />
    </>
  );
}
