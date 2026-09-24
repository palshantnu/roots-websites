import Reveal from '../common/Reveal';
import StatCard from '../cards/StatCard';
import { stats as defaultStats } from '../../data/stats';

/**
 * Animated company statistics. Pass `items` to override the defaults
 * (e.g. per-service stats).
 */
export default function StatsSection({
  items = defaultStats,
  eyebrow = 'Trusted by growing companies',
  title = 'Numbers that reflect the work',
  dark = true,
}) {
  return (
    <section className={dark ? 'section section--dark section--tight' : 'section section--tight'}>
      <div className="container">
        {(eyebrow || title) && (
          <div className="sec-head sec-head--center">
            {eyebrow && <span className="eyebrow">{eyebrow}</span>}
            {title && <h2>{title}</h2>}
          </div>
        )}
        <Reveal className="stats">
          {items.map((s) => (
            <StatCard key={s.id ?? s.label} stat={s} />
          ))}
        </Reveal>
      </div>
    </section>
  );
}
