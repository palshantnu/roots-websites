import { Link } from 'react-router-dom';
import { useSettings } from '../../hooks/useApi';

/**
 * Site logo. Uses the Roots Technology brand image (public/logo.png),
 * which already contains the wordmark, so no extra text is rendered.
 */
export default function BrandLogo({ onClick }) {
  const { settings } = useSettings();
  const label = settings?.siteName ?? 'Roots Technology';

  return (
    <Link to="/" className="brand brand--image" onClick={onClick} aria-label={`${label} home`}>
      <img src="/logo.png" alt={label} className="brand__logo" width="120" height="120" />
    </Link>
  );
}
