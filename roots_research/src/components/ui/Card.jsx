import { motion } from "framer-motion";

// Full literal class strings so Tailwind's static scanner can find them
// (dynamic template-built class names like `bg-${glow}` are not detected).
const glowStyles = {
  blue: { shadow: "hover:shadow-blue-400/25", bg: "bg-blue-400" },
  ink: { shadow: "hover:shadow-ink-900/20", bg: "bg-ink-800" },
};

/**
 * Rounded, soft-shadow card with a lift + accent-glow + hairline blue
 * border-glow hover state — the shared premium card shell.
 */
export default function Card({ children, className = "", glow = "blue", ...props }) {
  const tone = glowStyles[glow] || glowStyles.blue;
  return (
    <motion.div
      whileHover={{ y: -8 }}
      transition={{ type: "spring", stiffness: 300, damping: 22 }}
      className={`group gradient-border relative rounded-3xl border border-ink-900/10 dark:border-white/10 bg-white/90 dark:bg-white/[0.04] backdrop-blur-sm p-6 md:p-8 shadow-md shadow-ink-900/5 transition-shadow duration-300 hover:shadow-2xl ${tone.shadow} ${className}`}
      {...props}
    >
      <div
        className={`pointer-events-none absolute -inset-px rounded-3xl opacity-0 blur-xl transition-opacity duration-500 group-hover:opacity-[0.08] ${tone.bg}`}
        aria-hidden="true"
      />
      <div className="relative">{children}</div>
    </motion.div>
  );
}
