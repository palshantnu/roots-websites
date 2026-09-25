import { FiArrowRight } from 'react-icons/fi';
import Badge from '../common/Badge';
import SmartImage from '../common/SmartImage';

/**
 * Project card. `project` = { name, industry, category, technologies[], description, image }.
 * `onOpen` (optional) makes the whole card a button that opens a details modal.
 */
export default function ProjectCard({ project, onOpen }) {
  const Wrapper = onOpen ? 'button' : 'article';
  const wrapperProps = onOpen
    ? { onClick: () => onOpen(project), type: 'button', 'aria-label': `View ${project.name} case study` }
    : {};

  return (
    <Wrapper
      className="card card--hover proj-card proj-card--hover"
      style={onOpen ? { textAlign: 'left', cursor: 'pointer', font: 'inherit' } : undefined}
      {...wrapperProps}
    >
      <div className="proj-card__media">
        <SmartImage src={project.image} alt={project.name} loading="lazy" />
        <span className="proj-card__tag">
          <Badge>{project.category}</Badge>
        </span>
      </div>
      <div className="proj-card__body">
        <span className="proj-card__meta">{project.industry}</span>
        <h3>{project.name}</h3>
        <p>{project.description}</p>
        <div className="chip-row">
          {(project.technologies ?? []).map((t) => (
            <span key={t} className="badge badge--soft">
              {t}
            </span>
          ))}
        </div>
        <span className="link-arrow">
          View Case Study <FiArrowRight />
        </span>
      </div>
    </Wrapper>
  );
}
