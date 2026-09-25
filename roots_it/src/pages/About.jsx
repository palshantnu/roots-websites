import { FiCheck } from 'react-icons/fi';
import SEO from '../components/common/SEO';
import SectionTitle from '../components/common/SectionTitle';
import Reveal from '../components/common/Reveal';
import AsyncState from '../components/common/AsyncState';
import SmartImage from '../components/common/SmartImage';
import PageHero from '../components/templates/PageHero';
import StatsSection from '../components/sections/StatsSection';
import CTASection from '../components/sections/CTASection';
import ValueCard from '../components/cards/ValueCard';
import { Icon } from '../utils/iconMap';
import { fillTokens, paragraphs } from '../utils/content';
import { usePage, useSection, useSettings } from '../hooks/useApi';

export default function About() {
  const { page } = usePage('about');
  const { settings } = useSettings();
  const intro = useSection('about_intro').items[0];
  const culture = useSection('about_culture').items[0];
  const cultureSection = useSection('culture_points');
  const missionVision = useSection('mission_vision').items;
  const values = useSection('values');
  const whyChooseUs = useSection('why_choose_us');
  const team = useSection('team');

  const tokens = {
    years: settings?.foundedYear ? new Date().getFullYear() - settings.foundedYear : '',
    team_count: team.items.length,
  };

  return (
    <>
      <SEO page={page} />

      <PageHero page={page} trail={[{ label: 'About Us' }]} actions={[{ label: 'Work With Us', to: '/contact' }]} />

      {/* Introduction / Who we are */}
      {intro && (
        <section className="section section--muted">
          <div className="container">
            <div className="split">
              <Reveal className="split__media">
                <SmartImage src={intro.image} alt={`The ${settings?.siteName ?? ''} team collaborating`} />
              </Reveal>
              <Reveal delay={0.1}>
                <span className="eyebrow">Who we are</span>
                <h2 style={{ margin: '14px 0 16px' }}>{intro.title}</h2>
                {paragraphs(fillTokens(intro.description, tokens)).map((text, i, all) => (
                  <p key={text.slice(0, 24)} style={i < all.length - 1 ? { marginBottom: 14 } : undefined}>
                    {text}
                  </p>
                ))}
              </Reveal>
            </div>
          </div>
        </section>
      )}

      {/* Mission & Vision */}
      {missionVision.length > 0 && (
        <section className="section">
          <div className="container">
            <div className="grid grid-2">
              {missionVision.map((item, i) => (
                <Reveal key={item.id} delay={i * 0.1}>
                  <article className="card" style={{ height: '100%' }}>
                    <span className="icon-tile icon-tile--gradient" aria-hidden="true">
                      <Icon name={item.icon} />
                    </span>
                    <h3 style={{ margin: '16px 0 10px' }}>{item.title}</h3>
                    <p>{item.description}</p>
                  </article>
                </Reveal>
              ))}
            </div>
          </div>
        </section>
      )}

      {/* Values */}
      <section className="section section--muted">
        <div className="container">
          <SectionTitle eyebrow="Our values" title="The principles behind every decision" align="center" />
          <AsyncState loading={values.loading} error={values.error} empty={values.items.length === 0} onRetry={values.reload}>
            <div className="grid grid-3 values-grid">
              {values.items.map((v, i) => (
                <Reveal key={v.id} delay={(i % 3) * 0.05}>
                  <ValueCard item={v} />
                </Reveal>
              ))}
            </div>
          </AsyncState>
        </div>
      </section>

      {/* Why Roots Technology */}
      <section className="section">
        <div className="container">
          <SectionTitle eyebrow="Why Roots Technology" title="What working with us feels like" align="center" />
          <AsyncState loading={whyChooseUs.loading} error={whyChooseUs.error} empty={whyChooseUs.items.length === 0} onRetry={whyChooseUs.reload}>
            <div className="grid grid-4">
              {whyChooseUs.items.map((item, i) => (
                <Reveal key={item.id} delay={(i % 4) * 0.05}>
                  <article className="card card--hover svc-card">
                    <span className="icon-tile" aria-hidden="true">
                      <Icon name={item.icon} />
                    </span>
                    <h3 style={{ fontSize: '1.05rem' }}>{item.title}</h3>
                    <p>{item.description}</p>
                  </article>
                </Reveal>
              ))}
            </div>
          </AsyncState>
        </div>
      </section>

      {/* Team */}
      <section className="section section--muted">
        <div className="container">
          <SectionTitle eyebrow="Our team" title="The people you’ll actually work with" align="center" />
          <AsyncState loading={team.loading} error={team.error} empty={team.items.length === 0} onRetry={team.reload}>
            <div className="team-grid">
              {team.items.map((member, i) => (
                <Reveal key={member.id} delay={(i % 4) * 0.05}>
                  <div className="member">
                    <div className="member__photo">
                      <SmartImage src={member.image} alt={member.title} loading="lazy" />
                    </div>
                    <p className="member__name">{member.title}</p>
                    <p className="member__role">{member.subtitle}</p>
                  </div>
                </Reveal>
              ))}
            </div>
          </AsyncState>
        </div>
      </section>

      <StatsSection eyebrow="Company statistics" title="Where we stand today" />

      {/* Work culture */}
      {(culture || cultureSection.items.length > 0) && (
        <section className="section">
          <div className="container">
            <div className="split split--reverse">
              <Reveal className="split__media">
                <SmartImage src={culture?.image} alt={`${settings?.siteName ?? ''} work culture`} />
              </Reveal>
              <Reveal delay={0.1}>
                <span className="eyebrow">Work culture</span>
                <h2 style={{ margin: '14px 0 18px' }}>{culture?.title}</h2>
                <ul className="feature-list">
                  {cultureSection.items.map((c) => (
                    <li key={c.id}>
                      <FiCheck />
                      {c.title}
                    </li>
                  ))}
                </ul>
              </Reveal>
            </div>
          </div>
        </section>
      )}

      <CTASection
        cta={page?.cta}
        primary={{ label: 'Get a Free Consultation', to: '/contact' }}
        secondary={{ label: 'View Our Work', to: '/portfolio' }}
      />
    </>
  );
}
