import { Fragment } from 'react';
import { Link } from 'react-router-dom';

/**
 * `trail` is [{ label, to }]. The last item renders as plain text (current page).
 */
export default function Breadcrumbs({ trail = [] }) {
  return (
    <nav className="breadcrumbs" aria-label="Breadcrumb">
      <Link to="/">Home</Link>
      {trail.map((item, i) => {
        const isLast = i === trail.length - 1;
        return (
          <Fragment key={item.label}>
            <span aria-hidden="true">/</span>
            {isLast || !item.to ? (
              <span>{item.label}</span>
            ) : (
              <Link to={item.to}>{item.label}</Link>
            )}
          </Fragment>
        );
      })}
    </nav>
  );
}
