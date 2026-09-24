import { Link } from 'react-router-dom';
import { FiArrowRight } from 'react-icons/fi';
import { Icon } from '../../utils/iconMap';

/**
 * Service card. `service` = { title, description, icon, link }.
 * `iconVariant`: gradient | default | dark
 */
export default function ServiceCard({ service, iconVariant = 'default' }) {
  const tileClass =
    iconVariant === 'gradient'
      ? 'icon-tile icon-tile--gradient'
      : iconVariant === 'dark'
      ? 'icon-tile icon-tile--dark'
      : 'icon-tile';

  return (
    <article className="card card--hover svc-card">
      <span className={tileClass} aria-hidden="true">
        <Icon name={service.icon} />
      </span>
      <h3>{service.title}</h3>
      <p>{service.description}</p>
      {service.link && (
        <Link to={service.link} className="link-arrow">
          Learn more <FiArrowRight />
        </Link>
      )}
    </article>
  );
}
