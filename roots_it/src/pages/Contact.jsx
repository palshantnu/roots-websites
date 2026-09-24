import { FiMail, FiPhone, FiMapPin, FiClock } from 'react-icons/fi';
import SEO from '../components/common/SEO';
import PageHero from '../components/templates/PageHero';
import Reveal from '../components/common/Reveal';
import SectionTitle from '../components/common/SectionTitle';
import FAQ from '../components/common/FAQ';
import ContactForm from '../components/forms/ContactForm';
import { site } from '../data/site';
import { generalFaqs } from '../data/faqs';

export default function Contact() {
  // Swap this for a real API call when the backend exists.
  const handleSubmit = async (values) => {
    // await fetch(`${import.meta.env.VITE_API_BASE_URL}/leads`, {
    //   method: 'POST', headers: { 'Content-Type': 'application/json' },
    //   body: JSON.stringify(values),
    // });
    console.info('Lead captured (mock):', values);
    await new Promise((r) => setTimeout(r, 800));
  };

  const info = [
    { icon: <FiMail />, title: 'Email us', body: site.email, href: `mailto:${site.email}` },
    { icon: <FiPhone />, title: 'Call us', body: site.phone, href: `tel:${site.phoneHref}` },
    {
      icon: <FiMapPin />,
      title: 'Visit us',
      body: `${site.address.line1}, ${site.address.line2}`,
    },
    { icon: <FiClock />, title: 'Working hours', body: site.workingHours },
  ];

  return (
    <>
      <SEO
        title="Contact"
        description="Talk to Roots Technology about your project. Get a free consultation, request a quote or ask us anything — we reply within one business day."
      />

      <PageHero
        eyebrow="Contact"
        title="Let’s Discuss Your Project"
        subtitle="Tell us what you’re trying to build or grow. We’ll reply within one business day with next steps — or a straight answer if we’re not the right fit."
        trail={[{ label: 'Contact' }]}
      />

      <section className="section">
        <div className="container">
          <div className="contact-grid">
            <Reveal className="contact-info">
              {info.map((item) => (
                <div className="contact-info__item" key={item.title}>
                  <span className="icon-tile" aria-hidden="true">
                    {item.icon}
                  </span>
                  <div>
                    <h4>{item.title}</h4>
                    {item.href ? (
                      <p>
                        <a href={item.href}>{item.body}</a>
                      </p>
                    ) : (
                      <p>{item.body}</p>
                    )}
                  </div>
                </div>
              ))}
            </Reveal>

            <Reveal className="contact-card" delay={0.1}>
              <h2 style={{ marginBottom: 6 }}>Send us a message</h2>
              <p style={{ marginBottom: 20 }}>
                Fields marked <strong>*</strong> are required.
              </p>
              <ContactForm onSubmit={handleSubmit} />
            </Reveal>
          </div>

          <Reveal className="map-embed" delay={0.1}>
            {/* Replace with a real <iframe> Google Maps embed */}
            Google Maps — {site.mapQuery}
          </Reveal>
        </div>
      </section>

      <section className="section section--muted">
        <div className="container container--narrow">
          <SectionTitle eyebrow="Before you ask" title="Frequently asked questions" align="center" />
          <FAQ items={generalFaqs} defaultOpen={0} />
        </div>
      </section>
    </>
  );
}
