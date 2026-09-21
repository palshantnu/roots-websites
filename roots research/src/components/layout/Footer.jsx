import { Link } from "react-router-dom";
import { motion } from "framer-motion";
import { GraduationCap, Mail, Phone, MapPin } from "lucide-react";
import { navLinks } from "../../data/nav";
import { FacebookIcon, TwitterIcon, InstagramIcon, LinkedinIcon } from "../ui/SocialIcons";

const socials = [
  { icon: FacebookIcon, label: "Facebook", href: "https://facebook.com" },
  { icon: TwitterIcon, label: "Twitter / X", href: "https://twitter.com" },
  { icon: InstagramIcon, label: "Instagram", href: "https://instagram.com" },
  { icon: LinkedinIcon, label: "LinkedIn", href: "https://linkedin.com" },
];

const quickLinks = [
  ...navLinks,
  { label: "Mock Viva", to: "/mock-viva" },
  { label: "Workshops", to: "/workshops" },
  { label: "Blog", to: "/blog" },
];

export default function Footer() {
  return (
    <footer className="relative isolate overflow-hidden border-t-2 border-blue-100 bg-white text-ink-600 dark:border-white/10 dark:bg-ink-950 dark:text-ink-300">
      <div className="pointer-events-none absolute inset-0 -z-10 opacity-[0.1]" aria-hidden="true">
        <div className="absolute -top-40 right-0 h-96 w-96 rounded-full bg-blue-300 blur-[120px]" />
      </div>
      <div className="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div className="grid grid-cols-1 gap-12 sm:grid-cols-2 lg:grid-cols-4">
          <div>
            <Link to="/" className="flex items-center gap-2.5">
              <span className="flex h-10 w-10 items-center justify-center rounded-xl border border-blue-200 bg-white text-blue-600 shadow-lg shadow-blue-500/10">
                <GraduationCap className="h-5 w-5" aria-hidden="true" />
              </span>
              <span className="font-display text-lg font-semibold text-ink-950 dark:text-ivory-50">
                ThesisCraft <span className="text-gradient-blue">Academy</span>
              </span>
            </Link>
            <p className="mt-4 max-w-xs text-sm leading-relaxed text-ink-500 dark:text-ink-300">
              An elite, mentor-led thesis writing and research consultancy for
              PhD and Masters scholars — original work, delivered on time.
            </p>
            <div className="mt-6 flex gap-3">
              {socials.map(({ icon: Icon, label, href }) => (
                <motion.a
                  key={label}
                  href={href}
                  target="_blank"
                  rel="noopener noreferrer"
                  aria-label={label}
                  whileHover={{ y: -4, scale: 1.08 }}
                  whileTap={{ scale: 0.94 }}
                  className="focus-ring flex h-10 w-10 items-center justify-center rounded-full border border-ink-900/10 bg-blue-50 text-ink-600 transition-colors hover:border-blue-400/50 hover:bg-blue-100 hover:text-blue-700 dark:border-white/10 dark:bg-white/5 dark:text-ink-200 dark:hover:bg-blue-400/10 dark:hover:text-blue-300"
                >
                  <Icon className="h-4.5 w-4.5" aria-hidden="true" />
                </motion.a>
              ))}
            </div>
          </div>

          <div>
            <h3 className="font-display text-sm font-semibold uppercase tracking-widest text-ink-950 dark:text-ivory-50">Quick Links</h3>
            <ul className="mt-5 flex flex-col gap-3">
              {quickLinks.map((link) => (
                <li key={link.label}>
                  <Link
                    to={link.to}
                    className="focus-ring text-sm text-ink-500 transition-colors hover:text-blue-600 dark:text-ink-300 dark:hover:text-blue-300"
                  >
                    {link.label}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          <div>
            <h3 className="font-display text-sm font-semibold uppercase tracking-widest text-ink-950 dark:text-ivory-50">Company</h3>
            <ul className="mt-5 flex flex-col gap-3">
              {[
                { label: "About Us", to: "/about" },
                { label: "Our Mentors", to: "/mentors" },
                { label: "Careers", to: "/careers" },
                { label: "Privacy Policy", to: "/privacy" },
                { label: "Terms of Service", to: "/terms" },
              ].map((link) => (
                <li key={link.label}>
                  <Link
                    to={link.to}
                    className="focus-ring text-sm text-ink-500 transition-colors hover:text-blue-600 dark:text-ink-300 dark:hover:text-blue-300"
                  >
                    {link.label}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          <div>
            <h3 className="font-display text-sm font-semibold uppercase tracking-widest text-ink-950 dark:text-ivory-50">Get in Touch</h3>
            <ul className="mt-5 flex flex-col gap-4 text-sm text-ink-500 dark:text-ink-300">
              <li className="flex items-start gap-3">
                <Mail className="mt-0.5 h-4.5 w-4.5 shrink-0 text-blue-500 dark:text-blue-400" aria-hidden="true" />
                <a href="mailto:hello@thesiscraftacademy.com" className="focus-ring hover:text-blue-600 dark:hover:text-blue-300">
                  hello@thesiscraftacademy.com
                </a>
              </li>
              <li className="flex items-start gap-3">
                <Phone className="mt-0.5 h-4.5 w-4.5 shrink-0 text-blue-500 dark:text-blue-400" aria-hidden="true" />
                <a href="tel:+911234567890" className="focus-ring hover:text-blue-600 dark:hover:text-blue-300">
                  +91 12345 67890
                </a>
              </li>
              <li className="flex items-start gap-3">
                <MapPin className="mt-0.5 h-4.5 w-4.5 shrink-0 text-blue-500 dark:text-blue-400" aria-hidden="true" />
                <span>HSR Layout, Bengaluru, Karnataka, India</span>
              </li>
            </ul>
          </div>
        </div>

        <div className="mt-14 flex flex-col items-center justify-between gap-4 border-t border-ink-900/10 pt-8 sm:flex-row dark:border-white/10">
          <p className="text-center text-xs text-ink-400 sm:text-left">
            © {new Date().getFullYear()} ThesisCraft Academy. All rights reserved.
          </p>
          <p className="text-center text-xs text-ink-400 sm:text-right">
            Designed for original academic mentoring — not a substitute for your own scholarship.
          </p>
        </div>
      </div>
    </footer>
  );
}
