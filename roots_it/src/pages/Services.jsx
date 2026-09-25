import SEO from '../components/common/SEO';
import SectionTitle from '../components/common/SectionTitle';
import AsyncState from '../components/common/AsyncState';
import PageHero from '../components/templates/PageHero';
import ServicesGrid from '../components/sections/ServicesGrid';
import ProcessTimeline from '../components/sections/ProcessTimeline';
import CTASection from '../components/sections/CTASection';
import FAQ from '../components/common/FAQ';
import { useFaqs, usePage, useSection, useServices } from '../hooks/useApi';

export default function Services() {
  const { page } = usePage('services');
  const { data: services, loading, error, reload } = useServices();
  const process = useSection('process_steps');
  const faqs = useFaqs();

  const itServices = services.filter((s) => s.category === 'IT Services');
  const marketingServices = services.filter((s) => s.category === 'Digital Marketing');

  return (
    <>
      <SEO page={page} />

      <PageHero
        page={page}
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
          <AsyncState loading={loading} error={error} empty={itServices.length === 0} onRetry={reload}>
            <ServicesGrid services={itServices} columns={3} iconVariant="gradient" />
          </AsyncState>
        </div>
      </section>

      {/* Digital Marketing */}
      <section className="section" id="digital-marketing">
        <div className="container">
          <SectionTitle eyebrow="Digital Marketing Services" title="Attract, convert and retain">
            Full-funnel marketing measured against pipeline and revenue — not
            impressions.
          </SectionTitle>
          <AsyncState loading={loading} error={error} empty={marketingServices.length === 0} onRetry={reload}>
            <ServicesGrid services={marketingServices} columns={3} />
          </AsyncState>
        </div>
      </section>

      {/* Process */}
      <section className="section section--dark">
        <div className="container">
          <SectionTitle eyebrow="Our process" title="The same rhythm on every engagement" align="center" />
          <div className="container--narrow" style={{ padding: 0, margin: '0 auto' }}>
            <AsyncState loading={process.loading} error={process.error} empty={process.items.length === 0} onRetry={process.reload}>
              <ProcessTimeline steps={process.items} />
            </AsyncState>
          </div>
        </div>
      </section>

      {/* FAQs */}
      <section className="section section--muted">
        <div className="container container--narrow">
          <SectionTitle eyebrow="FAQs" title="Common questions" align="center" />
          <AsyncState loading={faqs.loading} error={faqs.error} empty={faqs.data.length === 0} onRetry={faqs.reload}>
            <FAQ items={faqs.data.map((f) => ({ q: f.question, a: f.answer }))} defaultOpen={0} />
          </AsyncState>
        </div>
      </section>

      <CTASection
        cta={page?.cta}
        primary={{ label: 'Get a Free Consultation', to: '/contact' }}
        secondary={{ label: 'Contact Us', to: '/contact' }}
      />
    </>
  );
}
