import { FiMail, FiPhone, FiMapPin, FiClock } from 'react-icons/fi';
import SEO from '../components/common/SEO';
import PageHero from '../components/templates/PageHero';
import Reveal from '../components/common/Reveal';
import SectionTitle from '../components/common/SectionTitle';
import FAQ from '../components/common/FAQ';
import AsyncState from '../components/common/AsyncState';
import ContactForm from '../components/forms/ContactForm';
import { useFaqs, usePage, useSettings } from '../hooks/useApi';

export default function Contact() {
  const { page } = usePage('contact');
  const { settings } = useSettings();
  const faqs = useFaqs();
  const contact = settings?.contact ?? {};

  // Swap this for a real API call when lead capture is added to the backend.
  const handleSubmit = async (values) => {
    console.info('Lead captured (mock):', values);
    await new Promise((r) => setTimeout(r, 800));
  };

  const info = [
    contact.email && { icon: <FiMail />, title: 'Email us', body: contact.email, href: `mailto:${contact.email}` },
    contact.phone && { icon: <FiPhone />, title: 'Call us', body: contact.phone, href: `tel:${contact.phone.replace(/[^\d+]/g, '')}` },
    contact.address && { icon: <FiMapPin />, title: 'Visit us', body: contact.address.split('\n').join(', ') },
    contact.hours && { icon: <FiClock />, title: 'Working hours', body: contact.hours },
  ].filter(Boolean);

  return (
    <>
      <SEO page={page} />

      <PageHero page={page} trail={[{ label: 'Contact' }]} />

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

          {settings?.mapQuery && (
            <Reveal className="map-embed" delay={0.1}>
              {/* Replace with a real <iframe> Google Maps embed */}
              Google Maps — {settings.mapQuery}
            </Reveal>
          )}
        </div>
      </section>

      <section className="section section--muted">
        <div className="container container--narrow">
          <SectionTitle eyebrow="Before you ask" title="Frequently asked questions" align="center" />
          <AsyncState loading={faqs.loading} error={faqs.error} empty={faqs.data.length === 0} onRetry={faqs.reload}>
            <FAQ items={faqs.data.map((f) => ({ q: f.question, a: f.answer }))} defaultOpen={0} />
          </AsyncState>
        </div>
      </section>
    </>
  );
}
