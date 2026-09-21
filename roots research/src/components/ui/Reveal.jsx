import { motion } from "framer-motion";

/**
 * Scroll-triggered fade/slide-in wrapper used across every section for
 * consistent reveal animation.
 */
export default function Reveal({
  children,
  delay = 0,
  y = 28,
  duration = 0.6,
  once = true,
  className = "",
  ...props
}) {
  return (
    <motion.div
      initial={{ opacity: 0, y }}
      whileInView={{ opacity: 1, y: 0 }}
      viewport={{ once, margin: "-80px" }}
      transition={{ duration, delay, ease: [0.22, 1, 0.36, 1] }}
      className={className}
      {...props}
    >
      {children}
    </motion.div>
  );
}
