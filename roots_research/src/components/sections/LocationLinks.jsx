import { useState } from "react";
import { motion } from "framer-motion";
import { ChevronDown, MapPinned } from "lucide-react";
import Section from "../ui/Section";
import SectionHeading from "../ui/SectionHeading";
import Reveal from "../ui/Reveal";
import { useSection } from "../../hooks/useApi";

const INITIAL_COUNT = 16;

export default function LocationLinks() {
  const [expanded, setExpanded] = useState(false);
  const { items } = useSection("indian_cities");
  const indianCities = items.map((item) => item.title);
  const visibleCities = expanded ? indianCities : indianCities.slice(0, INITIAL_COUNT);

  return (
    <Section>
      <SectionHeading
        eyebrow="Local Support"
        eyebrowIcon={MapPinned}
        tone="ink"
        title="Search Thesis Writing by Location"
        description="On-ground familiarity with university formatting guidelines across India — find mentors who already know your institution's requirements."
      />

      <Reveal delay={0.1} className="mx-auto mt-12 flex max-w-4xl flex-wrap justify-center gap-2.5 sm:gap-3">
        {visibleCities.map((city, i) => (
          <motion.a
            key={city}
            href="/#contact"
            initial={{ opacity: 0, y: 10 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ delay: Math.min(i * 0.02, 0.3), duration: 0.3 }}
            whileHover={{ y: -3 }}
            className="focus-ring rounded-full border border-ink-900/15 bg-white px-4 py-2 text-xs font-medium text-ink-600 transition-colors hover:border-blue-400 hover:bg-blue-50 hover:text-blue-700 sm:text-sm dark:border-white/10 dark:bg-white/5 dark:text-ink-200 dark:hover:bg-blue-400/10 dark:hover:text-blue-300"
          >
            Thesis Writing in {city}
          </motion.a>
        ))}
      </Reveal>

      {indianCities.length > INITIAL_COUNT && (
      <div className="mt-8 flex justify-center">
        <button
          type="button"
          onClick={() => setExpanded((e) => !e)}
          aria-expanded={expanded}
          className="focus-ring flex items-center gap-2 rounded-full border border-ink-900/25 px-5 py-2.5 text-sm font-semibold text-ink-800 transition hover:border-blue-400 hover:text-blue-700 dark:text-ivory-100 dark:hover:text-blue-300"
        >
          {expanded ? "Show Less" : `Show More (${indianCities.length - INITIAL_COUNT}+)`}
          <motion.span animate={{ rotate: expanded ? 180 : 0 }} transition={{ duration: 0.3 }}>
            <ChevronDown className="h-4 w-4" />
          </motion.span>
        </button>
      </div>
      )}
    </Section>
  );
}
