import { motion } from 'framer-motion';
import { FiArrowRight, FiActivity, FiCheckCircle } from 'react-icons/fi';
import Button from '../common/Button';
import { avatarImage } from '../../utils/helpers';

const clientLogos = ['NorthPeak', 'Lumen', 'CareLoop', 'Meridian', 'Atlas Legal', 'GreenRoute'];
const avatars = ['h1', 'h2', 'h3', 'h4'].map(avatarImage);

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
              Software Development &amp; Digital Marketing
            </motion.span>

            <motion.h1 variants={fade} custom={1} initial="hidden" animate="show">
              Transform Your Ideas Into{' '}
              <span className="gradient-text">Powerful Digital Solutions</span>
            </motion.h1>

            <motion.p
              className="lead"
              variants={fade}
              custom={2}
              initial="hidden"
              animate="show"
            >
              Roots Technology helps businesses grow with innovative software
              development, web and mobile applications, custom software,
              e-commerce solutions and full-funnel digital marketing.
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
              <span className="hero__avatars">
                {avatars.map((src) => (
                  <img key={src} src={src} alt="" loading="lazy" />
                ))}
              </span>
              <span>Trusted by 50+ founders &amp; product teams worldwide</span>
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
          {clientLogos.map((name) => (
            <span key={name}>{name}</span>
          ))}
        </div>
      </div>
    </section>
  );
}
