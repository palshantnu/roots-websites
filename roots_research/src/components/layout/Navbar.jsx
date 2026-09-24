import { useEffect, useState } from "react";
import { createPortal } from "react-dom";
import { Link, NavLink } from "react-router-dom";
import { AnimatePresence, motion } from "framer-motion";
import { GraduationCap, Menu, X, Moon, Sun, ArrowRight } from "lucide-react";
import { navLinks } from "../../data/nav";
import { useTheme } from "../../context/ThemeContext";
import Button from "../ui/Button";

export default function Navbar() {
  const [scrolled, setScrolled] = useState(false);
  const [mobileOpen, setMobileOpen] = useState(false);
  const { theme, toggleTheme } = useTheme();

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 12);
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  useEffect(() => {
    document.body.style.overflow = mobileOpen ? "hidden" : "";
    return () => {
      document.body.style.overflow = "";
    };
  }, [mobileOpen]);

  const linkClass = ({ isActive }) =>
    `relative px-1 py-2 text-sm font-semibold tracking-tight transition-colors duration-200 focus-ring ${
      isActive
        ? "text-blue-600 dark:text-blue-300"
        : "text-ink-700 hover:text-blue-600 dark:text-ivory-200 dark:hover:text-blue-300"
    }`;

  return (
    <>
      <header
        className={`fixed inset-x-0 top-0 z-50 transition-all duration-300 ${
          scrolled
            ? "bg-white/80 dark:bg-ink-950/80 backdrop-blur-xl shadow-md shadow-ink-900/5 border-b border-ink-900/10 dark:border-white/5"
            : "bg-white/40 dark:bg-ink-950/40 backdrop-blur-md"
        }`}
      >
        <nav className="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8" aria-label="Primary">
          <Link to="/" className="flex items-center gap-2.5 focus-ring rounded-lg" aria-label="ThesisCraft Academy home">
            <motion.span
              whileHover={{ rotate: -6, scale: 1.06 }}
              className="flex h-10 w-10 items-center justify-center rounded-xl border border-blue-200 bg-white text-blue-600 shadow-lg shadow-blue-500/10"
            >
              <GraduationCap className="h-5 w-5" aria-hidden="true" />
            </motion.span>
            <span className="font-display text-lg font-semibold tracking-tight text-ink-950 dark:text-ivory-50">
              ThesisCraft <span className="text-gradient-blue">Academy</span>
            </span>
          </Link>

          <div className="hidden items-center gap-8 lg:flex">
            {navLinks.map((link) => (
              <NavLink key={link.to} to={link.to} className={linkClass}>
                {({ isActive }) => (
                  <>
                    {link.label}
                    {isActive && (
                      <motion.span
                        layoutId="nav-underline"
                        className="absolute -bottom-1 left-0 right-0 h-0.5 rounded-full bg-blue-400"
                      />
                    )}
                  </>
                )}
              </NavLink>
            ))}
          </div>

          <div className="hidden items-center gap-3 lg:flex">
            <button
              type="button"
              onClick={toggleTheme}
              aria-label={theme === "dark" ? "Switch to light mode" : "Switch to dark mode"}
              className="focus-ring flex h-10 w-10 items-center justify-center rounded-full border border-ink-900/15 text-ink-700 transition hover:border-blue-400 hover:text-blue-600 dark:border-white/10 dark:text-ivory-200 dark:hover:text-blue-300"
            >
              {theme === "dark" ? <Sun className="h-4.5 w-4.5" /> : <Moon className="h-4.5 w-4.5" />}
            </button>

            <Button to="/#contact" size="sm" icon={ArrowRight}>
              Get Free Consultation
            </Button>
          </div>

          <button
            type="button"
            className="focus-ring flex h-10 w-10 items-center justify-center rounded-full border border-ink-900/15 text-ink-800 lg:hidden dark:border-white/10 dark:text-ivory-200"
            onClick={() => setMobileOpen(true)}
            aria-label="Open menu"
            aria-expanded={mobileOpen}
          >
            <Menu className="h-5 w-5" />
          </button>
        </nav>
      </header>

      {/* Rendered via a portal: the header uses backdrop-blur, which creates
          a new containing block for fixed-position descendants and would
          collapse this drawer's height if it stayed nested inside <header>. */}
      {createPortal(
        <AnimatePresence>
          {mobileOpen && (
            <>
              <motion.div
                initial={{ opacity: 0 }}
                animate={{ opacity: 1 }}
                exit={{ opacity: 0 }}
                onClick={() => setMobileOpen(false)}
                className="fixed inset-0 z-40 bg-ink-950/60 backdrop-blur-sm lg:hidden"
                aria-hidden="true"
              />
              <motion.div
                initial={{ x: "100%" }}
                animate={{ x: 0 }}
                exit={{ x: "100%" }}
                transition={{ type: "spring", stiffness: 320, damping: 34 }}
                className="fixed inset-y-0 right-0 z-50 flex w-[85%] max-w-sm flex-col gap-6 overflow-y-auto bg-white p-6 shadow-2xl dark:bg-ink-950 lg:hidden"
                role="dialog"
                aria-modal="true"
                aria-label="Mobile navigation"
              >
                <div className="flex items-center justify-between">
                  <span className="font-display text-lg font-semibold text-gradient-blue">Menu</span>
                  <button
                    type="button"
                    onClick={() => setMobileOpen(false)}
                    aria-label="Close menu"
                    className="focus-ring flex h-9 w-9 items-center justify-center rounded-full border border-ink-900/15 text-ink-700 dark:border-white/10 dark:text-ivory-200"
                  >
                    <X className="h-4.5 w-4.5" />
                  </button>
                </div>

                <div className="flex flex-col gap-1">
                  {navLinks.map((link, i) => (
                    <motion.div
                      key={link.to}
                      initial={{ opacity: 0, x: 24 }}
                      animate={{ opacity: 1, x: 0 }}
                      transition={{ delay: 0.05 * i + 0.1 }}
                    >
                      <NavLink
                        to={link.to}
                        onClick={() => setMobileOpen(false)}
                        className={({ isActive }) =>
                          `block rounded-xl px-4 py-3 text-base font-semibold transition ${
                            isActive
                              ? "bg-gradient-blue text-white"
                              : "text-ink-700 hover:bg-ink-900/5 dark:text-ivory-200 dark:hover:bg-white/5"
                          }`
                        }
                      >
                        {link.label}
                      </NavLink>
                    </motion.div>
                  ))}
                </div>

                <div className="h-px bg-ink-900/10 dark:bg-white/10" />

                <button
                  type="button"
                  onClick={toggleTheme}
                  className="focus-ring flex items-center justify-center gap-2 rounded-full border border-ink-900/15 px-5 py-3 text-sm font-semibold text-ink-700 dark:border-white/10 dark:text-ivory-200"
                >
                  {theme === "dark" ? <Sun className="h-4 w-4" /> : <Moon className="h-4 w-4" />}
                  {theme === "dark" ? "Light Mode" : "Dark Mode"}
                </button>

                <Button to="/#contact" onClick={() => setMobileOpen(false)} className="mt-auto w-full" size="lg" icon={ArrowRight}>
                  Get Free Consultation
                </Button>
              </motion.div>
            </>
          )}
        </AnimatePresence>,
        document.body
      )}
    </>
  );
}
