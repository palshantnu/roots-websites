import Reveal from '../common/Reveal';
import StatCard from '../cards/StatCard';
import AsyncState from '../common/AsyncState';
import { useSection } from '../../hooks/useApi';
import { toNumber } from '../../utils/content';

/**
 * Animated company statistics (the "stats" section in the admin). Pass
 * `items` ({ value, suffix, label }) to override them, e.g. per-service stats.
 */
export default function StatsSection({
  items,
  eyebrow = 'Trusted by growing companies',
  title = 'Numbers that reflect the work',
  dark = true,
}) {
  const { items: companyStats, loading, error, reload } = useSection('stats');
  const stats =
    items ??
    companyStats.map((s) => ({ id: s.id, value: toNumber(s.value), suffix: s.suffix ?? '', label: s.title }));

  return (
    <section className={dark ? 'section section--dark section--tight' : 'section section--tight'}>
      <div className="container">
        {(eyebrow || title) && (
          <div className="sec-head sec-head--center">
            {eyebrow && <span className="eyebrow">{eyebrow}</span>}
            {title && <h2>{title}</h2>}
          </div>
        )}
        <AsyncState loading={!items && loading} error={!items && error} empty={stats.length === 0} onRetry={reload}>
          <Reveal className="stats">
            {stats.map((s) => (
              <StatCard key={s.id ?? s.label} stat={s} />
            ))}
          </Reveal>
        </AsyncState>
      </div>
    </section>
  );
}
