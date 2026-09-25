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
