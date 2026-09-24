import { classNames } from '../../utils/helpers';
import Reveal from './Reveal';

/**
 * Standard section header: eyebrow + heading + optional intro.
 * `align="center"` centres and constrains width.
 */
export default function SectionTitle({
  eyebrow,
  title,
  children,
  align = 'left',
  as: Heading = 'h2',
  className,
}) {
  return (
    <Reveal
      className={classNames(
        'sec-head',
        align === 'center' && 'sec-head--center',
        className
      )}
    >
      {eyebrow && <span className="eyebrow">{eyebrow}</span>}
      <Heading>{title}</Heading>
      {children && <p className="lead">{children}</p>}
    </Reveal>
  );
}
