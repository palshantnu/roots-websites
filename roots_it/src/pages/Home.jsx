import SEO from '../components/common/SEO';
import SectionTitle from '../components/common/SectionTitle';
import AsyncState from '../components/common/AsyncState';
import Hero from '../components/home/Hero';
import HomeServices from '../components/home/HomeServices';
import WhyChooseUs from '../components/home/WhyChooseUs';
import FeaturedProjects from '../components/home/FeaturedProjects';
import StatsSection from '../components/sections/StatsSection';
import ProcessTimeline from '../components/sections/ProcessTimeline';
import TechnologiesGrid from '../components/sections/TechnologiesGrid';
import TestimonialsSlider from '../components/sections/TestimonialsSlider';
import CTASection from '../components/sections/CTASection';
import { usePage, useSection } from '../hooks/useApi';

export default function Home() {
  const { page } = usePage('home');
  const { items: process, loading, error, reload } = useSection('process_steps');

  return (
    <>
      <SEO page={page} />

      <Hero />

      <StatsSection
        eyebrow="Trusted by growing companies"
        title="Delivering results since 2021"
      />

      <HomeServices />

      <WhyChooseUs />

      {/* Development process */}
      <section className="section section--dark">
        <div className="container">
          <SectionTitle
            eyebrow="How we work"
            title="A development process built on visibility"
            align="center"
          >
            Seven stages, each with a clear deliverable and a demo you can see.
          </SectionTitle>
          <div className="container--narrow" style={{ padding: 0, margin: '0 auto' }}>
            <AsyncState loading={loading} error={error} empty={process.length === 0} onRetry={reload}>
              <ProcessTimeline steps={process} />
            </AsyncState>
          </div>
        </div>
      </section>

      <FeaturedProjects />

      {/* Testimonials */}
      <section className="section">
        <div className="container container--narrow">
          <SectionTitle eyebrow="Testimonials" title="What our clients say" align="center" />
          <TestimonialsSlider />
        </div>
      </section>

      {/* Technologies */}
      <section className="section section--muted">
        <div className="container">
          <SectionTitle eyebrow="Our stack" title="Technologies we use" align="center">
            Modern, well-supported tools chosen for longevity and performance.
          </SectionTitle>
          <TechnologiesGrid />
        </div>
      </section>

      <CTASection
        cta={page?.cta}
        title="Let’s Build Something Amazing Together"
        primary={{ label: 'Start Your Project', to: '/contact' }}
        secondary={{ label: 'Get Free Consultation', to: '/contact' }}
      />
    </>
  );
}
