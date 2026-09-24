import { useEffect, useRef, useState } from "react";
import { useInView, animate } from "framer-motion";

/**
 * Counts up from 0 to `value` once it scrolls into view.
 */
export default function AnimatedCounter({ value, suffix = "", prefix = "", duration = 2, className = "" }) {
  const ref = useRef(null);
  const isInView = useInView(ref, { once: true, margin: "-60px" });
  const [display, setDisplay] = useState(0);

  useEffect(() => {
    if (!isInView) return undefined;
    const controls = animate(0, value, {
      duration,
      ease: [0.22, 1, 0.36, 1],
      onUpdate(latest) {
        setDisplay(Math.round(latest));
      },
    });
    return () => controls.stop();
  }, [isInView, value, duration]);

  return (
    <span ref={ref} className={className}>
      {prefix}
      {display.toLocaleString()}
      {suffix}
    </span>
  );
}
