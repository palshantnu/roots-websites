import Reveal from '../common/Reveal';
import Button from '../common/Button';

/**
 * Reusable call-to-action band. Drop at the bottom of any page.
 */
export default function CTASection({
  title = "Let's Build Something Amazing Together",
  text = 'Tell us where you want to be in 12 months. We’ll show you how to get there — starting with a free, no-obligation consultation.',
  primary = { label: 'Start Your Project', to: '/contact' },
  secondary = { label: 'Talk to an Expert', to: '/contact' },
  bare = false,
}) {
  const inner = (
    <Reveal className="cta">
      <span className="eyebrow" style={{ color: 'var(--color-secondary)' }}>
        Get started
      </span>
      <h2>{title}</h2>
      <p>{text}</p>
      <div className="btn-row">
        {primary && (
          <Button to={primary.to} variant="primary" size="lg">
            {primary.label}
          </Button>
        )}
        {secondary && (
          <Button to={secondary.to} variant="ghost-light" size="lg">
            {secondary.label}
          </Button>
        )}
      </div>
    </Reveal>
  );

  if (bare) return inner;

  return (
    <section className="section">
      <div className="container">{inner}</div>
    </section>
  );
}
