import { useCallback, useEffect, useState } from 'react';
import { AnimatePresence, motion } from 'framer-motion';
import { FiChevronLeft, FiChevronRight } from 'react-icons/fi';
import TestimonialCard from '../cards/TestimonialCard';
import AsyncState from '../common/AsyncState';
import { useTestimonials } from '../../hooks/useApi';

/** Auto-advancing testimonial slider with manual controls + dots. */
export default function TestimonialsSlider({ interval = 6500 }) {
  const { data: items, loading, error, reload } = useTestimonials();
  const [index, setIndex] = useState(0);
  const [dir, setDir] = useState(1);
  const count = items.length;

  const go = useCallback(
    (next) => {
      setDir(next > index || (index === count - 1 && next === 0) ? 1 : -1);
      setIndex((next + count) % count);
    },
    [index, count]
  );

  useEffect(() => {
    if (count <= 1) return;
    const id = setInterval(() => {
      setDir(1);
      setIndex((i) => (i + 1) % count);
    }, interval);
    return () => clearInterval(id);
  }, [count, interval]);

  const variants = {
    enter: (d) => ({ opacity: 0, x: d > 0 ? 40 : -40 }),
    center: { opacity: 1, x: 0 },
    exit: (d) => ({ opacity: 0, x: d > 0 ? -40 : 40 }),
  };

  if (count === 0 || !items[index]) {
    return <AsyncState loading={loading} error={error} empty onRetry={reload} emptyText="No testimonials yet." />;
  }

  return (
    <div className="tst">
      <div className="tst__viewport">
        <AnimatePresence mode="wait" custom={dir}>
          <motion.div
            key={items[index].id}
            custom={dir}
            variants={variants}
            initial="enter"
            animate="center"
            exit="exit"
            transition={{ duration: 0.4, ease: [0.22, 1, 0.36, 1] }}
          >
            <TestimonialCard item={items[index]} />
          </motion.div>
        </AnimatePresence>
      </div>

      <div className="tst__nav">
        <button className="tst__btn" onClick={() => go(index - 1)} aria-label="Previous testimonial">
          <FiChevronLeft />
        </button>
        <button className="tst__btn" onClick={() => go(index + 1)} aria-label="Next testimonial">
          <FiChevronRight />
        </button>
      </div>

      <div className="tst__dots">
        {items.map((it, i) => (
          <button
            key={it.id}
            className={`tst__dot ${i === index ? 'is-active' : ''}`}
            onClick={() => go(i)}
            aria-label={`Go to testimonial ${i + 1}`}
            aria-current={i === index}
          />
        ))}
      </div>
    </div>
  );
}
