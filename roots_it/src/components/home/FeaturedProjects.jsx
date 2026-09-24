import { FiArrowRight } from 'react-icons/fi';
import SectionTitle from '../common/SectionTitle';
import Reveal from '../common/Reveal';
import ProjectCard from '../cards/ProjectCard';
import Button from '../common/Button';
import { featuredProjects } from '../../data/projects';

/** Home "Featured Projects" — first 6 featured projects, link to Portfolio. */
export default function FeaturedProjects() {
  return (
    <section className="section section--muted">
      <div className="container">
        <SectionTitle eyebrow="Selected work" title="Projects we’re proud of" align="center">
          A snapshot of recent product builds and growth programmes.
        </SectionTitle>

        <div className="grid grid-3">
          {featuredProjects.slice(0, 6).map((project, i) => (
            <Reveal key={project.id} delay={(i % 3) * 0.06}>
              <ProjectCard project={project} />
            </Reveal>
          ))}
        </div>

        <div className="btn-row" style={{ marginTop: 40, justifyContent: 'center' }}>
          <Button to="/portfolio" variant="outline" size="lg">
            View Full Portfolio <FiArrowRight />
          </Button>
        </div>
      </div>
    </section>
  );
}
