import { motion, useScroll, useTransform } from "framer-motion";
import { GraduationCap, BookOpen, PenTool, Sparkles, ScrollText, FileText } from "lucide-react";

const icons = [
  { Icon: GraduationCap, top: "12%", left: "8%", size: 40, duration: 7, delay: 0, hideOnMobile: false, tone: "ink", depth: 1 },
  { Icon: BookOpen, top: "68%", left: "6%", size: 34, duration: 8, delay: 1, hideOnMobile: true, tone: "blue", depth: 2 },
  { Icon: PenTool, top: "22%", left: "88%", size: 36, duration: 6.5, delay: 0.5, hideOnMobile: false, tone: "blue", depth: 1 },
  { Icon: Sparkles, top: "78%", left: "84%", size: 28, duration: 5.5, delay: 1.5, hideOnMobile: true, tone: "ink", depth: 3 },
  { Icon: ScrollText, top: "48%", left: "94%", size: 30, duration: 9, delay: 0.8, hideOnMobile: true, tone: "blue", depth: 2 },
  { Icon: FileText, top: "88%", left: "22%", size: 26, duration: 6, delay: 1.2, hideOnMobile: true, tone: "ink", depth: 3 },
];

const toneStyles = {
  ink: "bg-white border border-blue-200 text-blue-600",
  blue: "bg-gradient-blue text-white",
};

/** One icon: a scroll-linked parallax offset (outer) plus an autonomous
 * drift loop (inner), so the two motions compose without fighting over the
 * same `y` transform channel. */
function FloatingIcon({ Icon, top, left, size, duration, delay, hideOnMobile, tone, depth, scrollYProgress }) {
  const parallaxY = useTransform(scrollYProgress, [0, 1], [0, -70 * depth]);

  return (
    <motion.div
      className={`absolute ${hideOnMobile ? "hidden sm:block" : ""}`}
      style={{ top, left, y: parallaxY }}
    >
      <motion.div
        animate={{ y: [0, -18, 0], rotate: [0, 7, 0] }}
        transition={{ duration, delay, repeat: Infinity, ease: "easeInOut" }}
      >
        <div
          className={`flex items-center justify-center rounded-2xl shadow-lg shadow-ink-900/10 ${toneStyles[tone]}`}
          style={{ width: size + 22, height: size + 22 }}
        >
          <Icon style={{ width: size * 0.5, height: size * 0.5 }} />
        </div>
      </motion.div>
    </motion.div>
  );
}

export default function FloatingIcons() {
  const { scrollYProgress } = useScroll();

  return (
    <div className="pointer-events-none absolute inset-0 -z-10" aria-hidden="true">
      {icons.map((props, i) => (
        <FloatingIcon key={i} {...props} scrollYProgress={scrollYProgress} />
      ))}
    </div>
  );
}
