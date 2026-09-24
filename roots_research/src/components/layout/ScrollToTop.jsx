import { useEffect } from "react";
import { useLocation } from "react-router-dom";

/**
 * Resets scroll position on route change, or smooth-scrolls to an in-page
 * anchor (e.g. navigating to `/#contact` from another page).
 */
export default function ScrollToTop() {
  const { pathname, hash } = useLocation();

  useEffect(() => {
    if (hash) {
      const el = document.querySelector(hash);
      if (el) {
        const timer = setTimeout(() => el.scrollIntoView({ behavior: "smooth", block: "start" }), 100);
        return () => clearTimeout(timer);
      }
    }
    window.scrollTo(0, 0);
    return undefined;
  }, [pathname, hash]);

  return null;
}
