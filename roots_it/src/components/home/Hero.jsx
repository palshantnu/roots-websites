import { motion } from 'framer-motion';
import { FiArrowRight, FiActivity, FiCheckCircle } from 'react-icons/fi';
import Button from '../common/Button';
import { usePage, useSection } from '../../hooks/useApi';

/** Title with the admin-chosen `highlight` phrase in the gradient style. */
function HighlightedTitle({ title, highlight }) {
  if (!title) return null;
  const index = highlight ? title.indexOf(highlight) : -1;
  if (index < 0) return title;
  return (
    <>
      {title.slice(0, index)}
      <span className="gradient-text">{highlight}</span>
      {title.slice(index + highlight.length)}
    </>
  );
}

const CODE_SNIPPET = [
  '// ship roots-technology',
  'const launch = async () => {',
  '  await build("production");',
  '  await tests.run();',
  '  return deploy({ perf: 98, seo: 100 });',
  '};',
].join('\n');

const fade = {
  hidden: { opacity: 0, y: 22 },
  show: (i = 0) => ({
    opacity: 1,
    y: 0,
    transition: { duration: 0.6, ease: [0.22, 1, 0.36, 1], delay: i * 0.1 },
  }),
};

export default function Hero() {
  const { page } = usePage('home');
  const { items: trust } = useSection('hero_trust');
  const { items: avatars } = useSection('hero_avatars');
  const { items: clientLogos } = useSection('client_logos');

  return (
    <section className="hero">
      <div className="hero__bg" aria-hidden="true">
        <span />
        <span />
      </div>

      <div className="container">
        <div className="hero__grid">
          <div className="hero__content">
            <motion.span
              className="eyebrow"
              variants={fade}
              custom={0}
              initial="hidden"
              animate="show"
              style={{ color: 'var(--color-secondary)' }}
            >
              {page?.eyebrow}
            </motion.span>

            <motion.h1 variants={fade} custom={1} initial="hidden" animate="show">
              <HighlightedTitle title={page?.title} highlight={page?.highlight} />
            </motion.h1>

            <motion.p
              className="lead"
              variants={fade}
              custom={2}
              initial="hidden"
              animate="show"
            >
              {page?.description}
            </motion.p>

            <motion.div
              className="btn-row"
              variants={fade}
              custom={3}
              initial="hidden"
              animate="show"
            >
              <Button to="/contact" size="lg">
                Start Your Project <FiArrowRight />
              </Button>
              <Button to="/contact" variant="ghost-light" size="lg">
                Get Free Consultation
              </Button>
            </motion.div>

            <motion.div
              className="hero__trust"
              variants={fade}
              custom={4}
              initial="hidden"
              animate="show"
            >
              {avatars.length > 0 && (
                <span className="hero__avatars">
                  {avatars
                    .filter((avatar) => avatar.image)
                    .map((avatar) => (
                      <img key={avatar.id} src={avatar.image} alt="" loading="lazy" />
                    ))}
                </span>
              )}
              {trust[0] && <span>{trust[0].title}</span>}
            </motion.div>
          </div>

          <motion.div
            className="hero__visual"
            initial={{ opacity: 0, scale: 0.94, y: 20 }}
            animate={{ opacity: 1, scale: 1, y: 0 }}
            transition={{ duration: 0.7, ease: [0.22, 1, 0.36, 1], delay: 0.2 }}
          >
            <div className="code-panel">
              <div className="code-panel__dots">
                <i />
                <i />
                <i />
              </div>
              <code>{CODE_SNIPPET}</code>
            </div>

            <div className="hero__float hero__float--tl">
              <FiActivity />
              <span>
                98 / 100
                <small>Lighthouse performance</small>
              </span>
            </div>
            <div className="hero__float hero__float--br">
              <FiCheckCircle style={{ color: 'var(--color-secondary)' }} />
              <span>
                On-time delivery
                <small>Every sprint, demoed</small>
              </span>
            </div>
          </motion.div>
        </div>
      </div>

      <div className="logo-strip">
        <div className="container logo-strip__inner">
          {clientLogos.map((client) => (
            <span key={client.id}>{client.title}</span>
          ))}
        </div>
      </div>
    </section>
  );
}
