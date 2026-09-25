import { FiArrowRight } from 'react-icons/fi';
import SectionTitle from '../common/SectionTitle';
import Reveal from '../common/Reveal';
import ProjectCard from '../cards/ProjectCard';
import Button from '../common/Button';
import AsyncState from '../common/AsyncState';
import { useProjects } from '../../hooks/useApi';

/** Home "Featured Projects" — first 6 featured projects, link to Portfolio. */
export default function FeaturedProjects() {
  const { data: projects, loading, error, reload } = useProjects();
  const featuredProjects = projects.filter((p) => p.featured).slice(0, 6);

  return (
    <section className="section section--muted">
      <div className="container">
        <SectionTitle eyebrow="Selected work" title="Projects we’re proud of" align="center">
          A snapshot of recent product builds and growth programmes.
        </SectionTitle>
        <AsyncState
          loading={loading}
          error={error}
          empty={featuredProjects.length === 0}
          onRetry={reload}
          emptyText="Featured projects will appear here soon."
        >
          <div className="grid grid-3">
            {featuredProjects.map((project, i) => (
              <Reveal key={project.id} delay={(i % 3) * 0.06}>
                <ProjectCard project={project} />
              </Reveal>
            ))}
          </div>
        </AsyncState>
        <div className="btn-row" style={{ marginTop: 40, justifyContent: 'center' }}>
          <Button to="/portfolio" variant="outline" size="lg">
            View Full Portfolio <FiArrowRight />
          </Button>
        </div>
      </div>
    </section>
  );
}
