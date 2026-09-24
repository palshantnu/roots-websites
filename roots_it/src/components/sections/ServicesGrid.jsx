import Reveal from '../common/Reveal';
import ServiceCard from '../cards/ServiceCard';

/**
 * Responsive grid of ServiceCards.
 * `columns`: 2 | 3 (defaults to 3)
 */
export default function ServicesGrid({ services = [], columns = 3, iconVariant = 'default' }) {
  return (
    <div className={`grid grid-${columns}`}>
      {services.map((service, i) => (
        <Reveal key={service.id ?? service.slug} delay={(i % columns) * 0.06}>
          <ServiceCard service={service} iconVariant={iconVariant} />
        </Reveal>
      ))}
    </div>
  );
}
