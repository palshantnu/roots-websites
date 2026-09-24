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
import { site } from '../../data/site';
import { footerNav } from '../../data/navigation';

const socialIcons = {
  linkedin: FiLinkedin,
  instagram: FiInstagram,
  facebook: FiFacebook,
  x: FaXTwitter,
};

export default function Footer() {
  return (
    <footer className="footer">
      <div className="container">
        <div className="footer__top">
          <div className="footer__brand">
            <Link to="/" className="brand" aria-label={`${site.name} home`}>
              <span className="brand__mark">R</span>
              <span>
                Roots<span className="brand__sub">Technology</span>
              </span>
            </Link>
            <p>{site.tagline}. We build web &amp; mobile products, custom software and full-funnel marketing for ambitious teams.</p>

            <div className="footer__socials">
              {site.socials.map((s) => {
                const SIcon = socialIcons[s.icon] || FiMail;
                return (
                  <a
                    key={s.label}
                    href={s.href}
                    aria-label={s.label}
                    target="_blank"
                    rel="noopener noreferrer"
                  >
                    <SIcon />
                  </a>
                );
              })}
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
              <li>
                <FiMail />
                <a href={`mailto:${site.email}`}>{site.email}</a>
              </li>
              <li>
                <FiPhone />
                <a href={`tel:${site.phoneHref}`}>{site.phone}</a>
              </li>
              <li>
                <FiMapPin />
                <span>
                  {site.address.line1}
                  <br />
                  {site.address.line2}
                </span>
              </li>
            </ul>
          </div>
        </div>

        <div className="footer__bottom">
          <p>{site.copyright}</p>
          <nav aria-label="Legal">
            <Link to="/contact">Privacy Policy</Link>
            <Link to="/contact">Terms of Service</Link>
          </nav>
        </div>
      </div>
    </footer>
  );
}
