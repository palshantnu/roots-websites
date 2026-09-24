import { FiCheck } from 'react-icons/fi';
import SEO from '../components/common/SEO';
import SectionTitle from '../components/common/SectionTitle';
import Reveal from '../components/common/Reveal';
import PageHero from '../components/templates/PageHero';
import StatsSection from '../components/sections/StatsSection';
import CTASection from '../components/sections/CTASection';
import ValueCard from '../components/cards/ValueCard';
import { Icon } from '../utils/iconMap';
import { placeholderImage } from '../utils/helpers';
import { mission, vision, values, culture } from '../data/values';
import { whyChooseUs } from '../data/whyChooseUs';
import { team } from '../data/team';
import { site } from '../data/site';

export default function About() {
  return (
    <>
      <SEO
        title="About Us"
        description="Roots Technology is a remote-first software development and digital marketing agency founded in 2021. Meet the team and the values behind the work."
      />

      <PageHero
        eyebrow="About Roots Technology"
        title="A team that builds like it’s their own company"
        subtitle="We started Roots Technology in 2021 with a simple idea: pair senior engineering with honest marketing, and treat every client’s goals as our own."
        trail={[{ label: 'About Us' }]}
        actions={[{ label: 'Work With Us', to: '/contact' }]}
      />

      {/* Introduction / Who we are */}
      <section className="section section--muted">
        <div className="container">
          <div className="split">
            <Reveal className="split__media">
              <img src={placeholderImage('about-team', 900, 700)} alt="The Roots Technology team collaborating" />
            </Reveal>
            <Reveal delay={0.1}>
              <span className="eyebrow">Who we are</span>
              <h2 style={{ margin: '14px 0 16px' }}>
                Engineers, designers and marketers under one roof
              </h2>
              <p style={{ marginBottom: 14 }}>
                Roots Technology is a {new Date().getFullYear() - site.foundedYear}-year-old
                digital agency of {team.length}+ specialists. We design, build and
                grow web platforms, mobile apps, custom software and e-commerce
                experiences for founders and product teams around the world.
              </p>
              <p>
                We’re deliberately small and senior. You work directly with the
                people doing the work — no account-manager telephone game, no
                juniors learning on your budget.
              </p>
            </Reveal>
          </div>
        </div>
      </section>

      {/* Mission & Vision */}
      <section className="section">
        <div className="container">
          <div className="grid grid-2">
            <Reveal>
              <article className="card" style={{ height: '100%' }}>
                <span className="icon-tile icon-tile--gradient" aria-hidden="true">
                  <Icon name="target" />
                </span>
                <h3 style={{ margin: '16px 0 10px' }}>Our Mission</h3>
                <p>{mission}</p>
              </article>
            </Reveal>
            <Reveal delay={0.1}>
              <article className="card" style={{ height: '100%' }}>
                <span className="icon-tile icon-tile--gradient" aria-hidden="true">
                  <Icon name="eye" />
                </span>
                <h3 style={{ margin: '16px 0 10px' }}>Our Vision</h3>
                <p>{vision}</p>
              </article>
            </Reveal>
          </div>
        </div>
      </section>

      {/* Values */}
      <section className="section section--muted">
        <div className="container">
          <SectionTitle eyebrow="Our values" title="The principles behind every decision" align="center" />
          <div className="grid grid-3 values-grid">
            {values.map((v, i) => (
              <Reveal key={v.id} delay={(i % 3) * 0.05}>
                <ValueCard item={v} />
              </Reveal>
            ))}
          </div>
        </div>
      </section>

      {/* Why Roots Technology */}
      <section className="section">
        <div className="container">
          <SectionTitle eyebrow="Why Roots Technology" title="What working with us feels like" align="center" />
          <div className="grid grid-4">
            {whyChooseUs.map((item, i) => (
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
        </div>
      </section>

      {/* Team */}
      <section className="section section--muted">
        <div className="container">
          <SectionTitle eyebrow="Our team" title="The people you’ll actually work with" align="center" />
          <div className="team-grid">
            {team.map((member, i) => (
              <Reveal key={member.id} delay={(i % 4) * 0.05}>
                <div className="member">
                  <div className="member__photo">
                    <img src={member.avatar} alt={member.name} loading="lazy" />
                  </div>
                  <p className="member__name">{member.name}</p>
                  <p className="member__role">{member.role}</p>
                </div>
              </Reveal>
            ))}
          </div>
        </div>
      </section>

      <StatsSection eyebrow="Company statistics" title="Where we stand today" />

      {/* Work culture */}
      <section className="section">
        <div className="container">
          <div className="split split--reverse">
            <Reveal className="split__media">
              <img src={placeholderImage('about-culture', 900, 700)} alt="Roots Technology work culture" />
            </Reveal>
            <Reveal delay={0.1}>
              <span className="eyebrow">Work culture</span>
              <h2 style={{ margin: '14px 0 18px' }}>How we operate as a team</h2>
              <ul className="feature-list">
                {culture.map((c) => (
                  <li key={c}>
                    <FiCheck />
                    {c}
                  </li>
                ))}
              </ul>
            </Reveal>
          </div>
        </div>
      </section>

      <CTASection
        title="Let’s see if we’re a fit"
        primary={{ label: 'Get a Free Consultation', to: '/contact' }}
        secondary={{ label: 'View Our Work', to: '/portfolio' }}
      />
    </>
  );
}
