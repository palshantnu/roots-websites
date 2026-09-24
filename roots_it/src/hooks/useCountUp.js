import { useEffect, useRef, useState } from 'react';

/**
 * Animate a number from 0 -> `end` once the element scrolls into view.
 * Returns [value, ref].
 */
export function useCountUp(end = 0, { duration = 1600, start = 0 } = {}) {
  const [value, setValue] = useState(start);
  const ref = useRef(null);
  const hasRun = useRef(false);

  useEffect(() => {
    const node = ref.current;
    if (!node) return;

    const prefersReduced = window.matchMedia(
      '(prefers-reduced-motion: reduce)'
    ).matches;

    const run = () => {
      if (hasRun.current) return;
      hasRun.current = true;

      if (prefersReduced) {
        setValue(end);
        return;
      }

      const t0 = performance.now();
      const tick = (now) => {
        const p = Math.min((now - t0) / duration, 1);
        // easeOutCubic
        const eased = 1 - Math.pow(1 - p, 3);
        setValue(Math.round(start + (end - start) * eased));
        if (p < 1) requestAnimationFrame(tick);
      };
      requestAnimationFrame(tick);
    };

    const observer = new IntersectionObserver(
      (entries) => entries.forEach((e) => e.isIntersecting && run()),
      { threshold: 0.4 }
    );
    observer.observe(node);
    return () => observer.disconnect();
  }, [end, duration, start]);

  return [value, ref];
}

export default useCountUp;
