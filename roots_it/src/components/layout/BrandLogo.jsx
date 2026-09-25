import { Link } from 'react-router-dom';
import { useSettings } from '../../hooks/useApi';

/**
 * Site logo built from the admin site name: the first word is the logo text
 * and the rest the sub-label ("Roots Technology" -> "Roots" + "Technology").
 */
export default function BrandLogo({ onClick }) {
  const { settings } = useSettings();
  const [first = '', ...rest] = (settings?.siteName ?? '').trim().split(/\s+/);

  return (
    <Link to="/" className="brand" onClick={onClick} aria-label={`${settings?.siteName ?? ''} home`}>
      <span className="brand__mark">{first.charAt(0) || ' '}</span>
      <span>
        {first}
        {rest.length > 0 && <span className="brand__sub">{rest.join(' ')}</span>}
      </span>
    </Link>
  );
}
