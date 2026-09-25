import SectionTitle from '../common/SectionTitle';
import Reveal from '../common/Reveal';
import AsyncState from '../common/AsyncState';
import { Icon } from '../../utils/iconMap';
import { useSection } from '../../hooks/useApi';

/**
 * "Why Choose Roots Technology" benefit grid. The heading props let the
 * About page reuse the same admin-managed list with its own copy.
 */
export default function WhyChooseUs({
  eyebrow = 'Why Roots Technology',
  title = 'Built for teams who want a real partner',
  intro = 'Eight reasons clients stay with us long after the first project ships.',
}) {
  const { items, loading, error, reload } = useSection('why_choose_us');

  return (
    <section className="section">
      <div className="container">
        <SectionTitle eyebrow={eyebrow} title={title} align="center">
          {intro}
        </SectionTitle>
        <AsyncState loading={loading} error={error} empty={items.length === 0} onRetry={reload}>
          <div className="grid grid-4">
            {items.map((item, i) => (
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
  );
}
