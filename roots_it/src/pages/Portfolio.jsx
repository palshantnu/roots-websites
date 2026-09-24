import { useMemo, useState } from 'react';
import { AnimatePresence, motion } from 'framer-motion';
import { FiX } from 'react-icons/fi';
import SEO from '../components/common/SEO';
import PageHero from '../components/templates/PageHero';
import Reveal from '../components/common/Reveal';
import Badge from '../components/common/Badge';
import ProjectCard from '../components/cards/ProjectCard';
import CTASection from '../components/sections/CTASection';
import { useLockBodyScroll } from '../hooks/useLockBodyScroll';
import { projects, projectCategories } from '../data/projects';

function ProjectModal({ project, onClose }) {
  useLockBodyScroll(true);
  return (
    <motion.div
      className="modal-backdrop"
      onClick={onClose}
      initial={{ opacity: 0 }}
      animate={{ opacity: 1 }}
      exit={{ opacity: 0 }}
    >
      <motion.div
        className="modal"
        onClick={(e) => e.stopPropagation()}
        role="dialog"
        aria-modal="true"
        aria-label={project.name}
        initial={{ opacity: 0, y: 24, scale: 0.98 }}
        animate={{ opacity: 1, y: 0, scale: 1 }}
        exit={{ opacity: 0, y: 24, scale: 0.98 }}
        transition={{ duration: 0.25 }}
        style={{ position: 'relative' }}
      >
        <button className="modal__close" onClick={onClose} aria-label="Close">
          <FiX />
        </button>
        <div className="modal__media">
          <img src={project.image} alt={project.name} />
        </div>
        <div className="modal__body">
          <div className="chip-row">
            <Badge>{project.category}</Badge>
            <Badge variant="soft">{project.industry}</Badge>
            <Badge variant="soft">{project.year}</Badge>
          </div>
          <h2>{project.name}</h2>
          <p>{project.description}</p>
          <div>
            <strong style={{ fontFamily: 'var(--font-heading)' }}>Technologies</strong>
            <div className="chip-row" style={{ marginTop: 8 }}>
              {project.technologies.map((t) => (
                <span key={t} className="badge badge--soft">
                  {t}
                </span>
              ))}
            </div>
          </div>
        </div>
      </motion.div>
    </motion.div>
  );
}

export default function Portfolio() {
  const [filter, setFilter] = useState('All');
  const [active, setActive] = useState(null);

  const visible = useMemo(
    () =>
      filter === 'All'
        ? projects
        : projects.filter((p) => p.category === filter),
    [filter]
  );

  return (
    <>
      <SEO
        title="Portfolio"
        description="Selected work from Roots Technology — web platforms, mobile apps, custom software, e-commerce stores and digital marketing programmes."
      />

      <PageHero
        eyebrow="Portfolio"
        title="Work we’ve shipped and stand behind"
        subtitle="Filter by discipline to see how we approach different kinds of problems."
        trail={[{ label: 'Portfolio' }]}
        actions={[{ label: 'Start Your Project', to: '/contact' }]}
      />

      <section className="section">
        <div className="container">
          <div className="filter-bar" role="tablist" aria-label="Filter projects">
            {projectCategories.map((cat) => (
              <button
                key={cat}
                role="tab"
                aria-selected={filter === cat}
                className={`filter-chip ${filter === cat ? 'is-active' : ''}`}
                onClick={() => setFilter(cat)}
              >
                {cat}
              </button>
            ))}
          </div>

          <motion.div layout className="grid grid-3">
            <AnimatePresence mode="popLayout">
              {visible.map((project) => (
                <motion.div
                  key={project.id}
                  layout
                  initial={{ opacity: 0, scale: 0.96 }}
                  animate={{ opacity: 1, scale: 1 }}
                  exit={{ opacity: 0, scale: 0.96 }}
                  transition={{ duration: 0.25 }}
                >
                  <ProjectCard project={project} onOpen={setActive} />
                </motion.div>
              ))}
            </AnimatePresence>
          </motion.div>

          {visible.length === 0 && (
            <Reveal className="text-center">
              <p className="lead mx-auto">No projects in this category yet — check back soon.</p>
            </Reveal>
          )}
        </div>
      </section>

      <AnimatePresence>
        {active && <ProjectModal project={active} onClose={() => setActive(null)} />}
      </AnimatePresence>

      <CTASection
        title="Have a project like these in mind?"
        primary={{ label: 'Start Your Project', to: '/contact' }}
        secondary={{ label: 'Read Case Studies', to: '/case-studies' }}
      />
    </>
  );
}
