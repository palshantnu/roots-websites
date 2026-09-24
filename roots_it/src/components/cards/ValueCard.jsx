import { Icon } from '../../utils/iconMap';

/** Value / benefit card. `item` = { title, description, icon } */
export default function ValueCard({ item, iconVariant = 'default' }) {
  return (
    <article className="card card--hover svc-card">
      <span
        className={
          iconVariant === 'gradient'
            ? 'icon-tile icon-tile--gradient'
            : 'icon-tile'
        }
        aria-hidden="true"
      >
        <Icon name={item.icon} />
      </span>
      <h3>{item.title}</h3>
      <p>{item.description}</p>
    </article>
  );
}
