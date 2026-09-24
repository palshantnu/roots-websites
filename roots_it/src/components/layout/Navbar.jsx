import { useState } from 'react';
import { NavLink, Link } from 'react-router-dom';
import { FiChevronDown, FiMenu, FiX } from 'react-icons/fi';
import { AnimatePresence, motion } from 'framer-motion';
import { primaryNav } from '../../data/navigation';
import { site } from '../../data/site';
import { useScrolled } from '../../hooks/useScrollPosition';
import { useLockBodyScroll } from '../../hooks/useLockBodyScroll';
import { Icon } from '../../utils/iconMap';
import { classNames } from '../../utils/helpers';
import Button from '../common/Button';

function Brand({ onClick }) {
  return (
    <Link to="/" className="brand" onClick={onClick} aria-label={`${site.name} home`}>
      <span className="brand__mark">R</span>
      <span>
        Roots<span className="brand__sub">Technology</span>
      </span>
    </Link>
  );
}

/* ---------------- Desktop ---------------- */
function DesktopMenu() {
  return (
    <nav className="nav__menu" aria-label="Primary">
      {primaryNav.map((item) =>
        item.mega ? (
          <div className="nav__item" key={item.label}>
            <NavLink
              to={item.to}
              className={({ isActive }) =>
                classNames('nav__link', isActive && 'is-active')
              }
            >
              {item.label}
              <FiChevronDown className="nav__caret" />
            </NavLink>

            <div className="mega" role="menu">
              {item.mega.map((col) => (
                <div key={col.title}>
                  <p className="mega__col-title">{col.title}</p>
                  {col.links.map((link) => (
                    <Link key={link.to} to={link.to} className="mega__link" role="menuitem">
                      <span className="icon-tile" aria-hidden="true">
                        <Icon name={link.icon} />
                      </span>
                      <span>{link.label}</span>
                    </Link>
                  ))}
                </div>
              ))}
            </div>
          </div>
        ) : (
          <NavLink
            key={item.label}
            to={item.to}
            end={item.to === '/'}
            className={({ isActive }) =>
              classNames('nav__link', isActive && 'is-active')
            }
          >
            {item.label}
          </NavLink>
        )
      )}
    </nav>
  );
}

/* ---------------- Mobile drawer ---------------- */
function MobileDrawer({ onClose }) {
  const [openGroup, setOpenGroup] = useState(null);

  return (
    <>
      <motion.div
        className="drawer-backdrop"
        onClick={onClose}
        initial={{ opacity: 0 }}
        animate={{ opacity: 1 }}
        exit={{ opacity: 0 }}
      />
      <motion.div
        className="drawer"
        role="dialog"
        aria-label="Menu"
        initial={{ x: '100%' }}
        animate={{ x: 0 }}
        exit={{ x: '100%' }}
        transition={{ type: 'tween', duration: 0.3, ease: [0.22, 1, 0.36, 1] }}
      >
        <div className="drawer__head">
          <Brand onClick={onClose} />
          <button className="drawer__close" onClick={onClose} aria-label="Close menu">
            <FiX />
          </button>
        </div>

        {primaryNav.map((item) =>
          item.mega ? (
            <div key={item.label}>
              <button
                className="drawer__group-btn"
                aria-expanded={openGroup === item.label}
                onClick={() =>
                  setOpenGroup(openGroup === item.label ? null : item.label)
                }
              >
                {item.label}
                <FiChevronDown
                  style={{
                    transform:
                      openGroup === item.label ? 'rotate(180deg)' : 'none',
                    transition: 'transform .2s',
                  }}
                />
              </button>
              <AnimatePresence initial={false}>
                {openGroup === item.label && (
                  <motion.div
                    className="drawer__sub"
                    initial={{ height: 0 }}
                    animate={{ height: 'auto' }}
                    exit={{ height: 0 }}
                    transition={{ duration: 0.25 }}
                  >
                    {item.mega.flatMap((col) => [
                      <p
                        key={col.title}
                        className="mega__col-title"
                        style={{ paddingLeft: 24 }}
                      >
                        {col.title}
                      </p>,
                      ...col.links.map((link) => (
                        <NavLink
                          key={link.to}
                          to={link.to}
                          className="drawer__sublink"
                          onClick={onClose}
                        >
                          {link.label}
                        </NavLink>
                      )),
                    ])}
                  </motion.div>
                )}
              </AnimatePresence>
            </div>
          ) : (
            <NavLink
              key={item.label}
              to={item.to}
              end={item.to === '/'}
              className={({ isActive }) =>
                classNames('drawer__link', isActive && 'is-active')
              }
              onClick={onClose}
            >
              {item.label}
            </NavLink>
          )
        )}

        <Button to="/contact" className="drawer__cta" block onClick={onClose}>
          Get a Quote
        </Button>
      </motion.div>
    </>
  );
}

/* ---------------- Navbar ---------------- */
export default function Navbar() {
  const [mobileOpen, setMobileOpen] = useState(false);
  const scrolled = useScrolled(12);
  useLockBodyScroll(mobileOpen);

  return (
    <header className={classNames('nav', (scrolled || mobileOpen) && 'nav--solid')}>
      <div className="container nav__inner">
        <Brand />
        <DesktopMenu />
        <div className="nav__actions">
          <Button to="/contact" size="sm">
            Get a Quote
          </Button>
          <button
            className="nav__burger"
            aria-label="Open menu"
            aria-expanded={mobileOpen}
            onClick={() => setMobileOpen(true)}
          >
            <FiMenu />
          </button>
        </div>
      </div>

      <AnimatePresence>
        {mobileOpen && <MobileDrawer onClose={() => setMobileOpen(false)} />}
      </AnimatePresence>
    </header>
  );
}
