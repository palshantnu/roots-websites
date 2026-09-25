import { motion } from "framer-motion";
import Section from "../ui/Section";
import SectionHeading from "../ui/SectionHeading";
import Reveal from "../ui/Reveal";
import Card from "../ui/Card";
import { getIcon } from "../../lib/icons";

const iconTone = {
  ink: "bg-white border border-blue-200 text-blue-600",
  blue: "bg-gradient-blue text-white",
};

export default function OfferingsGrid({ eyebrow, eyebrowIcon, title, description, offerings, tone = "blue" }) {
  return (
    <Section className="bg-ivory-100/60 dark:bg-ink-900/20">
      <SectionHeading eyebrow={eyebrow} eyebrowIcon={eyebrowIcon} title={title} description={description} tone={tone} />
      <div className="mt-14 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        {offerings.map((item, i) => {
          const itemTone = iconTone[item.tone] ? item.tone : "ink";
          const ItemIcon = getIcon(item.icon);
          return (
            <Reveal key={item.id} delay={i * 0.08}>
              <Card glow={itemTone} className="h-full">
                <motion.div
                  whileHover={{ rotate: -6, scale: 1.08 }}
                  className={`flex h-12 w-12 items-center justify-center rounded-2xl shadow-md ${iconTone[itemTone]}`}
                >
                  <ItemIcon className="h-5.5 w-5.5" aria-hidden="true" />
                </motion.div>
                <h3 className="mt-5 font-display text-lg font-semibold text-ink-950 dark:text-ivory-50">{item.title}</h3>
                <p className="mt-2.5 text-sm leading-relaxed text-ink-600 dark:text-ink-200">{item.description}</p>
              </Card>
            </Reveal>
          );
        })}
      </div>
    </Section>
  );
}
