import { Link } from 'react-router-dom';
import {
  FiMail,
  FiPhone,
  FiMapPin,
  FiLinkedin,
  FiInstagram,
  FiFacebook,
} from 'react-icons/fi';
import { FaXTwitter } from 'react-icons/fa6';
import { footerNav } from '../../data/navigation';
import { useSettings } from '../../hooks/useApi';
import BrandLogo from './BrandLogo';

const socialNetworks = [
  { key: 'linkedin', label: 'LinkedIn', icon: FiLinkedin },
  { key: 'instagram', label: 'Instagram', icon: FiInstagram },
  { key: 'facebook', label: 'Facebook', icon: FiFacebook },
  { key: 'twitter', label: 'Twitter / X', icon: FaXTwitter },
];

export default function Footer() {
  const { settings } = useSettings();
  const contact = settings?.contact ?? {};
  const addressLines = (contact.address ?? '').split('\n').filter(Boolean);
  const socials = socialNetworks.filter((s) => settings?.socials?.[s.key]);

  return (
    <footer className="footer">
      <div className="container">
        <div className="footer__top">
          <div className="footer__brand">
            <BrandLogo />
            <p>{[settings?.tagline && `${settings.tagline}.`, settings?.footerText].filter(Boolean).join(' ')}</p>
            <div className="footer__socials">
              {socials.map(({ key, label, icon: SIcon }) => (
                <a
                  key={key}
                  href={settings.socials[key]}
                  aria-label={label}
                  target="_blank"
                  rel="noopener noreferrer"
                >
                  <SIcon />
                </a>
              ))}
            </div>
          </div>

          <div className="footer__col">
            <h4>{footerNav.company.title}</h4>
            <ul>
              {footerNav.company.links.map((l) => (
                <li key={l.to}>
                  <Link to={l.to}>{l.label}</Link>
                </li>
              ))}
            </ul>
          </div>

          <div className="footer__col">
            <h4>{footerNav.services.title}</h4>
            <ul>
              {footerNav.services.links.map((l) => (
                <li key={l.label}>
                  <Link to={l.to}>{l.label}</Link>
                </li>
              ))}
            </ul>
          </div>

          <div className="footer__col">
            <h4>Contact</h4>
            <ul className="footer__contact">
              {contact.email && (
                <li>
                  <FiMail />
                  <a href={`mailto:${contact.email}`}>{contact.email}</a>
                </li>
              )}
              {contact.phone && (
                <li>
                  <FiPhone />
                  <a href={`tel:${contact.phone.replace(/[^\d+]/g, '')}`}>{contact.phone}</a>
                </li>
              )}
              {addressLines.length > 0 && (
                <li>
                  <FiMapPin />
                  <span>
                    {addressLines.map((line, i) => (
                      <span key={line}>
                        {i > 0 && <br />}
                        {line}
                      </span>
                    ))}
                  </span>
                </li>
              )}
            </ul>
          </div>
        </div>

        <div
          style={{
            marginTop: '2.5rem',
            paddingTop: '1.75rem',
            borderTop: '1px solid rgba(255,255,255,0.12)',
          }}
        >
          <h4 style={{ marginBottom: '0.35rem',color:'white' }}>Explore our other services</h4>
          <p style={{ margin: '0 0 1.1rem', fontSize: '0.875rem', opacity: 0.75 }}>
            Part of the RTS group — discover what else we offer.
          </p>
          <div style={{ display: 'flex', flexWrap: 'wrap', gap: '0.85rem' }}>
            <a
              href="https://research.rootstechnology.in/"
              target="_blank"
              rel="noopener noreferrer"
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '0.5rem',
                padding: '0.75rem 1.5rem',
                borderRadius: '0.75rem',
                background: 'var(--color-primary, #0b63f6)',
                color: '#fff',
                fontSize: '0.875rem',
                fontWeight: 600,
                textDecoration: 'none',
                boxShadow: '0 2px 10px rgba(11,99,246,0.25)',
              }}
            >
              Visit RTS Research <span aria-hidden="true">→</span>
            </a>
            <a
              href="https://rtspublication.com/"
              target="_blank"
              rel="noopener noreferrer"
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '0.5rem',
                padding: '0.75rem 1.5rem',
                borderRadius: '0.75rem',
                border: '1px solid rgba(255,255,255,0.22)',
                color: 'inherit',
                fontSize: '0.875rem',
                fontWeight: 600,
                textDecoration: 'none',
              }}
            >
              Visit RTS Publication <span aria-hidden="true">→</span>
            </a>
          </div>
        </div>

        <div className="footer__bottom">
          <p>{settings?.footerNote}</p>
          <nav aria-label="Legal">
            <Link to="/contact">Privacy Policy</Link>
            <Link to="/contact">Terms of Service</Link>
          </nav>
        </div>
      </div>
    </footer>
  );
}
