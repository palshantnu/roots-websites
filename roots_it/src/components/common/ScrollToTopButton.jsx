import { AnimatePresence, motion } from 'framer-motion';
import { FiArrowUp } from 'react-icons/fi';
import { useScrolled } from '../../hooks/useScrollPosition';

/** Floating "back to top" button, appears after scrolling down a screen. */
export default function ScrollToTopButton() {
  const show = useScrolled(500);

  return (
    <AnimatePresence>
      {show && (
        <motion.button
          type="button"
          className="to-top"
          aria-label="Back to top"
          onClick={() => window.scrollTo({ top: 0, behavior: 'smooth' })}
          initial={{ opacity: 0, y: 16 }}
          animate={{ opacity: 1, y: 0 }}
          exit={{ opacity: 0, y: 16 }}
          transition={{ duration: 0.25 }}
        >
          <FiArrowUp />
        </motion.button>
      )}
    </AnimatePresence>
  );
}
