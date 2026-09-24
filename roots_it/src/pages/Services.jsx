import SEO from '../components/common/SEO';
import SectionTitle from '../components/common/SectionTitle';
import PageHero from '../components/templates/PageHero';
import ServicesGrid from '../components/sections/ServicesGrid';
import ProcessTimeline from '../components/sections/ProcessTimeline';
import CTASection from '../components/sections/CTASection';
import FAQ from '../components/common/FAQ';
import { itServices, marketingServices } from '../data/services';
import { process } from '../data/process';
import { generalFaqs } from '../data/faqs';

export default function Services() {
  return (
    <>
      <SEO
        title="Services"
        description="Roots Technology services: website & software development, mobile apps, custom software, e-commerce, SEO, social media, paid advertising and full-funnel digital marketing."
      />

      <PageHero
        eyebrow="Services"
        title="Everything you need to build and grow a digital product"
        subtitle="Two practices, one team. Engineering that ships reliable software, and marketing that turns it into measurable growth."
        trail={[{ label: 'Services' }]}
        actions={[
          { label: 'Request a Quote', to: '/contact' },
          { label: 'Talk to an Expert', to: '/contact', variant: 'ghost-light' },
        ]}
      />

      {/* IT Services */}
      <section className="section section--muted" id="it-services">
        <div className="container">
          <SectionTitle eyebrow="IT Services" title="Design, build and ship">
            Product engineering for web, mobile and internal systems — from a
            landing page to a multi-tenant platform.
          </SectionTitle>
          <ServicesGrid services={itServices} columns={3} iconVariant="gradient" />
        </div>
      </section>

      {/* Digital Marketing */}
      <section className="section" id="digital-marketing">
        <div className="container">
          <SectionTitle eyebrow="Digital Marketing Services" title="Attract, convert and retain">
            Full-funnel marketing measured against pipeline and revenue — not
            impressions.
          </SectionTitle>
          <ServicesGrid services={marketingServices} columns={3} />
        </div>
      </section>

      {/* Process */}
      <section className="section section--dark">
        <div className="container">
          <SectionTitle eyebrow="Our process" title="The same rhythm on every engagement" align="center" />
          <div className="container--narrow" style={{ padding: 0, margin: '0 auto' }}>
            <ProcessTimeline steps={process} />
          </div>
        </div>
      </section>

      {/* FAQs */}
      <section className="section section--muted">
        <div className="container container--narrow">
          <SectionTitle eyebrow="FAQs" title="Common questions" align="center" />
          <FAQ items={generalFaqs} defaultOpen={0} />
        </div>
      </section>

      <CTASection
        title="Not sure which service you need?"
        text="Book a free consultation. We’ll listen, ask the right questions and point you in the right direction — even if that’s not us."
        primary={{ label: 'Get a Free Consultation', to: '/contact' }}
        secondary={{ label: 'Contact Us', to: '/contact' }}
      />
    </>
  );
}
