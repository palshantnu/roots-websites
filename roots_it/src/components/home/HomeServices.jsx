import { FiArrowRight } from 'react-icons/fi';
import SectionTitle from '../common/SectionTitle';
import ServicesGrid from '../sections/ServicesGrid';
import Button from '../common/Button';
import AsyncState from '../common/AsyncState';
import { useServices } from '../../hooks/useApi';

/** Home "Services Overview" — IT services + Digital Marketing services. */
export default function HomeServices() {
  const { data: services, loading, error, reload } = useServices();
  const itServices = services.filter((s) => s.category === 'IT Services');
  const marketingServices = services.filter((s) => s.category === 'Digital Marketing');

  return (
    <section className="section section--muted" id="services">
      <div className="container">
        <SectionTitle eyebrow="What we do" title="Complete digital solutions, one partner" align="center">
          From the first line of code to the last conversion — Roots Technology
          covers the full journey of building and growing a digital product.
        </SectionTitle>

        <AsyncState loading={loading} error={error} empty={services.length === 0} onRetry={reload} emptyText="Our services will be listed here soon.">
          {itServices.length > 0 && (
            <>
              <h3 style={{ marginBottom: 20 }}>IT Services</h3>
              <ServicesGrid services={itServices} columns={3} iconVariant="gradient" />
            </>
          )}

          {marketingServices.length > 0 && (
            <>
              <h3 style={{ margin: '48px 0 20px' }}>Digital Marketing Services</h3>
              <ServicesGrid services={marketingServices.slice(0, 6)} columns={3} />
            </>
          )}
        </AsyncState>

        <div className="btn-row" style={{ marginTop: 40, justifyContent: 'center' }}>
          <Button to="/services" size="lg">
            Explore Services <FiArrowRight />
          </Button>
        </div>
      </div>
    </section>
  );
}
