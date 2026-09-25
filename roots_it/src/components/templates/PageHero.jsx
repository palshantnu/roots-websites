import Reveal from '../common/Reveal';
import Breadcrumbs from '../common/Breadcrumbs';
import Button from '../common/Button';

/**
 * Dark hero band for inner pages.
 * Pass `page` (from usePage) for the admin-managed eyebrow, title and
 * subtitle, or the props directly.
 * `trail` -> breadcrumbs, `actions` -> [{ label, to, variant }]
 */
export default function PageHero({ page, trail = [], actions = [], ...props }) {
  const eyebrow = props.eyebrow ?? page?.eyebrow;
  const title = props.title ?? page?.title;
  const subtitle = props.subtitle ?? page?.description;

  return (
    <section className="page-hero">
      <div className="container">
        <Reveal className="page-hero__inner">
          {trail.length > 0 && <Breadcrumbs trail={trail} />}
          {eyebrow && (
            <span className="eyebrow" style={{ color: 'var(--color-secondary)', marginTop: 14 }}>
              {eyebrow}
            </span>
          )}
          <h1>{title}</h1>
          {subtitle && <p>{subtitle}</p>}
          {actions.length > 0 && (
            <div className="btn-row">
              {actions.map((a) => (
                <Button key={a.label} to={a.to} variant={a.variant || 'primary'} size="lg">
                  {a.label}
                </Button>
              ))}
            </div>
          )}
        </Reveal>
      </div>
    </section>
  );
}
