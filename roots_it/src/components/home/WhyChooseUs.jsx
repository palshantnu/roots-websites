import SectionTitle from '../common/SectionTitle';
import Reveal from '../common/Reveal';
import { Icon } from '../../utils/iconMap';
import { whyChooseUs } from '../../data/whyChooseUs';

/** Home "Why Choose Roots Technology" — compact benefit grid. */
export default function WhyChooseUs() {
  return (
    <section className="section">
      <div className="container">
        <SectionTitle eyebrow="Why Roots Technology" title="Built for teams who want a real partner" align="center">
          Eight reasons clients stay with us long after the first project ships.
        </SectionTitle>

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
  );
}
