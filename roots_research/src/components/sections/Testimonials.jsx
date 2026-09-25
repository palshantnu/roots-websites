import { useCallback, useEffect, useState } from "react";
import { AnimatePresence, motion } from "framer-motion";
import { ChevronLeft, ChevronRight, Quote, Star, MessagesSquare } from "lucide-react";
import Section from "../ui/Section";
import SectionHeading from "../ui/SectionHeading";
import Reveal from "../ui/Reveal";
import AnimatedCounter from "../ui/AnimatedCounter";
import { useSection, useTestimonials } from "../../hooks/useApi";
import { toNumber } from "../../lib/format";

export default function Testimonials() {
  const [index, setIndex] = useState(0);
  const [direction, setDirection] = useState(1);
  const { data: testimonials } = useTestimonials();
  const { items: testimonialStats } = useSection("testimonial_stats");

  const goTo = useCallback(
    (next) => {
      setDirection(next > index || (index === testimonials.length - 1 && next === 0) ? 1 : -1);
      setIndex(next);
    },
    [index, testimonials.length]
  );

  const next = useCallback(() => goTo((index + 1) % testimonials.length), [goTo, index, testimonials.length]);
  const prev = useCallback(
    () => goTo((index - 1 + testimonials.length) % testimonials.length),
    [goTo, index, testimonials.length]
  );

  useEffect(() => {
    if (testimonials.length < 2) return undefined;
    const timer = setInterval(next, 6000);
    return () => clearInterval(timer);
  }, [next, testimonials.length]);

  const current = testimonials[index];

  return (
    <Section>
      <SectionHeading
        eyebrow="Scholar Voices"
        eyebrowIcon={MessagesSquare}
        tone="ink"
        title="Trusted by Researchers Worldwide"
        description="Real outcomes from real scholars — across disciplines, degree levels and deadlines."
      />

      <Reveal delay={0.1} className="mx-auto mt-12 grid max-w-3xl grid-cols-2 gap-4 sm:grid-cols-4 sm:gap-6">
        {testimonialStats.map((stat) => (
          <div key={stat.id} className="text-center">
            <p className="font-display text-2xl font-semibold text-gradient-blue sm:text-3xl">
              <AnimatedCounter value={toNumber(stat.value)} suffix={stat.suffix ?? ""} />
            </p>
            <p className="mt-1 text-xs font-medium text-ink-500 sm:text-sm dark:text-ink-300">{stat.title}</p>
          </div>
        ))}
      </Reveal>

      {current && (
      <Reveal delay={0.2} className="relative mx-auto mt-16 max-w-3xl">
        <div className="relative overflow-hidden rounded-[2rem] border border-blue-200/60 bg-white p-8 shadow-xl shadow-ink-900/5 sm:p-12 dark:border-white/10 dark:bg-white/[0.04]">
          <Quote className="h-10 w-10 text-blue-200 dark:text-blue-400/20" aria-hidden="true" />
          <div className="relative mt-2 min-h-[9.5rem] sm:min-h-[7.5rem]" aria-live="polite">
            <AnimatePresence mode="wait" custom={direction}>
              <motion.div
                key={index}
                custom={direction}
                initial={{ opacity: 0, x: direction * 40 }}
                animate={{ opacity: 1, x: 0 }}
                exit={{ opacity: 0, x: direction * -40 }}
                transition={{ duration: 0.4, ease: [0.22, 1, 0.36, 1] }}
              >
                <p className="font-display text-base leading-relaxed text-ink-700 sm:text-lg dark:text-ivory-100">
                  &ldquo;{current.quote}&rdquo;
                </p>
                <div className="mt-6 flex items-center justify-between">
                  <div>
                    <p className="font-display text-sm font-semibold text-ink-950 sm:text-base dark:text-ivory-50">
                      {current.name}
                    </p>
                    <p className="text-xs text-ink-500 sm:text-sm dark:text-ink-300">{current.role}</p>
                  </div>
                  <div className="flex gap-0.5">
                    {Array.from({ length: 5 }).map((_, i) => (
                      <Star
                        key={i}
                        className={`h-4 w-4 ${i < current.rating ? "fill-blue-400 text-blue-400" : "text-ink-200 dark:text-ink-700"}`}
                        aria-hidden="true"
                      />
                    ))}
                  </div>
                </div>
              </motion.div>
            </AnimatePresence>
          </div>
        </div>

        <div className="mt-6 flex items-center justify-center gap-4">
          <button
            type="button"
            onClick={prev}
            aria-label="Previous testimonial"
            className="focus-ring flex h-11 w-11 items-center justify-center rounded-full border border-ink-900/15 bg-white text-ink-600 shadow-sm transition hover:border-blue-400 hover:text-blue-600 dark:border-white/10 dark:bg-white/5 dark:text-ink-200"
          >
            <ChevronLeft className="h-5 w-5" />
          </button>
          <div className="flex gap-2">
            {testimonials.map((t, i) => (
              <button
                key={t.id}
                type="button"
                onClick={() => goTo(i)}
                aria-label={`Go to testimonial ${i + 1}`}
                aria-current={i === index}
                className={`h-2.5 rounded-full transition-all duration-300 ${
                  i === index ? "w-7 bg-blue-400" : "w-2.5 bg-ink-200 dark:bg-white/15"
                }`}
              />
            ))}
          </div>
          <button
            type="button"
            onClick={next}
            aria-label="Next testimonial"
            className="focus-ring flex h-11 w-11 items-center justify-center rounded-full border border-ink-900/15 bg-white text-ink-600 shadow-sm transition hover:border-blue-400 hover:text-blue-600 dark:border-white/10 dark:bg-white/5 dark:text-ink-200"
          >
            <ChevronRight className="h-5 w-5" />
          </button>
        </div>
      </Reveal>
      )}
    </Section>
  );
}
