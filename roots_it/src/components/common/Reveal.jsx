import { motion } from 'framer-motion';

/**
 * Scroll-reveal wrapper. Animates once when it enters the viewport.
 * Respects prefers-reduced-motion automatically via Framer Motion.
 *
 * <Reveal as="section" delay={0.1}>…</Reveal>
 */
const variants = {
  hidden: { opacity: 0, y: 24 },
  visible: (delay = 0) => ({
    opacity: 1,
    y: 0,
    transition: { duration: 0.55, ease: [0.22, 1, 0.36, 1], delay },
  }),
};

export default function Reveal({
  children,
  as = 'div',
  delay = 0,
  className,
  ...rest
}) {
  const MotionTag = motion[as] || motion.div;
  return (
    <MotionTag
      className={className}
      variants={variants}
      custom={delay}
      initial="hidden"
      whileInView="visible"
      viewport={{ once: true, margin: '-60px' }}
      {...rest}
    >
      {children}
    </MotionTag>
  );
}
