import { motion } from "framer-motion";
import { BadgeCheck, ShieldCheck } from "lucide-react";
import Section from "../ui/Section";
import SectionHeading from "../ui/SectionHeading";
import Reveal from "../ui/Reveal";
import { useSection } from "../../hooks/useApi";
import { getIcon } from "../../lib/icons";

const iconTone = {
  ink: "bg-white border border-blue-200 text-blue-600",
  blue: "bg-gradient-blue text-white",
};

const statTone = {
  ink: "bg-white border border-blue-200 text-blue-700",
  blue: "bg-gradient-blue text-white",
};

export default function WhyChooseUs() {
  const { items: whyChooseUs } = useSection("why_choose_us");
  const { items: trustBadges } = useSection("trust_badges");

  return (
    <Section className="bg-ivory-100/60 dark:bg-ink-900/20">
      <SectionHeading
        eyebrow="Why Scholars Choose Us"
        eyebrowIcon={ShieldCheck}
        tone="ink"
        title="Built for Trust, Backed by Results"
        description="No ghost-written guarantees, no vague promises — just a transparent process built around your academic integrity."
      />

      <div className="mt-14 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        {whyChooseUs.map((item, i) => {
          const tone = iconTone[item.tone] ? item.tone : "ink";
          const ItemIcon = getIcon(item.icon);
          return (
          <Reveal key={item.id} delay={i * 0.1}>
            <motion.div
              whileHover={{ y: -6 }}
              className="group relative h-full overflow-hidden rounded-3xl border border-ink-900/10 bg-white p-6 shadow-sm transition-shadow duration-300 hover:shadow-xl dark:border-white/10 dark:bg-white/[0.04]"
            >
              <span className="font-display text-4xl font-semibold text-ink-100 transition-colors duration-300 group-hover:text-blue-200 dark:text-white/5 dark:group-hover:text-blue-400/10">
                {String(i + 1).padStart(2, "0")}
              </span>
              <div
                className={`-mt-6 flex h-12 w-12 items-center justify-center rounded-2xl shadow-md ${iconTone[tone]}`}
              >
                <ItemIcon className="h-5.5 w-5.5" aria-hidden="true" />
              </div>
              <h3 className="mt-4 font-display text-lg font-semibold text-ink-950 dark:text-ivory-50">{item.title}</h3>
              <p className="mt-2 text-sm leading-relaxed text-ink-600 dark:text-ink-200">{item.description}</p>
              {item.meta && (
                <span
                  className={`mt-4 inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold ${statTone[tone]}`}
                >
                  {item.meta}
                </span>
              )}
            </motion.div>
          </Reveal>
          );
        })}
      </div>

      <Reveal delay={0.3} className="mt-14 flex flex-wrap items-center justify-center gap-3 sm:gap-4">
        {trustBadges.map((badge) => (
          <span
            key={badge.id}
            className="flex items-center gap-2 rounded-full border border-blue-300/50 bg-blue-50 px-4 py-2 text-xs font-semibold text-blue-800 sm:text-sm dark:border-blue-400/20 dark:bg-blue-400/10 dark:text-blue-200"
          >
            <BadgeCheck className="h-4 w-4" aria-hidden="true" />
            {badge.title}
          </span>
        ))}
      </Reveal>
    </Section>
  );
}
