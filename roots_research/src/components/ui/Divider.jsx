import { motion } from "framer-motion";

/**
 * Thin accent-colored line that draws itself in from the center when it
 * scrolls into view — used between major sections instead of a hard rule.
 */
export default function Divider({ className = "" }) {
  return (
    <div className={`flex items-center justify-center py-2 ${className}`} aria-hidden="true">
      <motion.span
        initial={{ scaleX: 0, opacity: 0 }}
        whileInView={{ scaleX: 1, opacity: 1 }}
        viewport={{ once: true, margin: "-40px" }}
        transition={{ duration: 0.9, ease: [0.22, 1, 0.36, 1] }}
        className="h-px w-24 bg-gradient-to-r from-transparent via-blue-400 to-transparent sm:w-32"
      />
      <span className="mx-3 h-1.5 w-1.5 rotate-45 bg-blue-400" />
      <motion.span
        initial={{ scaleX: 0, opacity: 0 }}
        whileInView={{ scaleX: 1, opacity: 1 }}
        viewport={{ once: true, margin: "-40px" }}
        transition={{ duration: 0.9, ease: [0.22, 1, 0.36, 1] }}
        className="h-px w-24 bg-gradient-to-l from-transparent via-blue-400 to-transparent sm:w-32"
      />
    </div>
  );
}
