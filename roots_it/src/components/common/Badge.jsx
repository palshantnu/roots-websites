import { classNames } from '../../utils/helpers';

/** Small pill label. variant: default | soft | dark */
export default function Badge({ children, variant = 'default', className }) {
  return (
    <span
      className={classNames(
        'badge',
        variant !== 'default' && `badge--${variant}`,
        className
      )}
    >
      {children}
    </span>
  );
}
