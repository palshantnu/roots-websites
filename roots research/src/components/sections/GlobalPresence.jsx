import { useMemo, useState } from "react";
import { AnimatePresence, motion } from "framer-motion";
import { Globe2, MapPin } from "lucide-react";
import Section from "../ui/Section";
import SectionHeading from "../ui/SectionHeading";
import Reveal from "../ui/Reveal";
import { regions, globalCities } from "../../data/cities";

export default function GlobalPresence() {
  const [active, setActive] = useState("All");

  const filtered = useMemo(
    () => (active === "All" ? globalCities : globalCities.filter((c) => c.region === active)),
    [active]
  );

  return (
    <Section className="bg-ivory-100/60 dark:bg-ink-900/20">
      <SectionHeading
        eyebrow="Where We Work"
        eyebrowIcon={Globe2}
        title="Our Global Presence"
        description="Scholars across six continents trust ThesisCraft Academy for research support, regardless of time zone."
      />

      <Reveal delay={0.1} className="mt-10 flex flex-wrap items-center justify-center gap-2.5">
        {regions.map((region) => (
          <motion.button
            key={region}
            type="button"
            onClick={() => setActive(region)}
            aria-pressed={active === region}
            whileTap={{ scale: 0.95 }}
            className={`focus-ring relative rounded-full px-4 py-2 text-xs font-semibold transition-colors duration-300 sm:text-sm ${
              active === region
                ? "text-white"
                : "border border-ink-900/15 bg-white text-ink-600 hover:border-blue-400 hover:text-blue-700 dark:border-white/10 dark:bg-white/5 dark:text-ink-200"
            }`}
          >
            {active === region && (
              <motion.span
                layoutId="region-pill"
                className="absolute inset-0 rounded-full bg-gradient-blue shadow-md shadow-blue-500/30"
                transition={{ type: "spring", stiffness: 400, damping: 30 }}
              />
            )}
            <span className="relative">{region}</span>
          </motion.button>
        ))}
      </Reveal>

      <motion.div
        layout
        className="mt-10 grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-4 md:grid-cols-4 lg:grid-cols-5"
      >
        <AnimatePresence mode="popLayout">
          {filtered.map((c) => (
            <motion.button
              key={`${c.city}-${c.country}`}
              type="button"
              layout
              initial={{ opacity: 0, scale: 0.85 }}
              animate={{ opacity: 1, scale: 1 }}
              exit={{ opacity: 0, scale: 0.85 }}
              whileHover={{ y: -5, scale: 1.03 }}
              transition={{ duration: 0.3 }}
              className="group flex flex-col items-center gap-2 rounded-2xl border border-ink-900/10 bg-white px-3 py-5 text-center shadow-sm transition-shadow hover:shadow-lg hover:border-blue-300 dark:border-white/10 dark:bg-white/[0.04]"
            >
              <span className="text-3xl transition-transform duration-300 group-hover:scale-110">{c.flag}</span>
              <span className="flex items-center gap-1 text-sm font-semibold text-ink-800 dark:text-ivory-100">
                <MapPin className="h-3.5 w-3.5 text-blue-500" aria-hidden="true" />
                {c.city}
              </span>
              <span className="text-xs text-ink-500 dark:text-ink-300">{c.country}</span>
            </motion.button>
          ))}
        </AnimatePresence>
      </motion.div>
    </Section>
  );
}
