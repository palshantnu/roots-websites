import Reveal from '../common/Reveal';
import { TechIcon } from '../../utils/iconMap';
import { technologyGroups } from '../../data/technologies';

const allTechnologies = technologyGroups.flatMap((group) => group.items);

/** Single auto-scrolling carousel of every technology pill (no grouping into rows). */
export default function TechnologiesGrid({ items = allTechnologies }) {
  const track = [...items, ...items];

  return (
    <Reveal className="tech-carousel">
      <div className="tech-carousel__track">
        {track.map((tech, i) => (
          <span className="tech-pill" key={`${tech.name}-${i}`}>
            <TechIcon name={tech.icon} />
            {tech.name}
          </span>
        ))}
      </div>
    </Reveal>
  );
}
