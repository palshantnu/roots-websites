import Reveal from '../common/Reveal';
import AsyncState from '../common/AsyncState';
import { TechIcon } from '../../utils/iconMap';
import { useSection } from '../../hooks/useApi';

/** Single auto-scrolling carousel of every technology pill (no grouping into rows). */
export default function TechnologiesGrid() {
  const { items, loading, error, reload } = useSection('technologies');
  const track = [...items, ...items];

  return (
    <AsyncState loading={loading} error={error} empty={items.length === 0} onRetry={reload}>
      <Reveal className="tech-carousel">
        <div className="tech-carousel__track">
          {track.map((tech, i) => (
            <span className="tech-pill" key={`${tech.id}-${i}`}>
              <TechIcon name={tech.value} />
              {tech.title}
            </span>
          ))}
        </div>
      </Reveal>
    </AsyncState>
  );
}
