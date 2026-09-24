import { Link } from 'react-router-dom';
import { classNames } from '../../utils/helpers';

/**
 * Polymorphic button.
 * - `to`   -> internal <Link>
 * - `href` -> external <a>
 * - otherwise -> <button>
 *
 * variant: primary | secondary | outline | ghost-light
 * size:    sm | md | lg
 */
export default function Button({
  children,
  to,
  href,
  variant = 'primary',
  size = 'md',
  block = false,
  className,
  ...rest
}) {
  const cls = classNames(
    'btn',
    `btn--${variant}`,
    size !== 'md' && `btn--${size}`,
    block && 'btn--block',
    className
  );

  if (to) {
    return (
      <Link to={to} className={cls} {...rest}>
        {children}
      </Link>
    );
  }

  if (href) {
    return (
      <a
        href={href}
        className={cls}
        target="_blank"
        rel="noopener noreferrer"
        {...rest}
      >
        {children}
      </a>
    );
  }

  return (
    <button className={cls} {...rest}>
      {children}
    </button>
  );
}
