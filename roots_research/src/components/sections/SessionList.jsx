import { motion } from "framer-motion";
import { CalendarDays, User2, ArrowRight } from "lucide-react";
import Section from "../ui/Section";
import Reveal from "../ui/Reveal";
import Button from "../ui/Button";

export default function SessionList({ items, metaKey = "seats", metaIcon: MetaIcon = User2 }) {
  return (
    <Section>
      <div className="grid grid-cols-1 gap-5 sm:grid-cols-2">
        {items.map((item, i) => (
          <Reveal key={item.title} delay={i * 0.08}>
            <motion.div
              whileHover={{ y: -5 }}
              className="flex h-full flex-col justify-between overflow-hidden rounded-3xl border border-ink-900/10 bg-white p-6 shadow-sm transition-shadow hover:shadow-xl dark:border-white/10 dark:bg-white/[0.04]"
            >
              <div>
                <span className={`inline-block h-1.5 w-14 rounded-full ${item.tone === "blue" ? "bg-gradient-blue" : "bg-blue-200"}`} />
                <h3 className="mt-4 font-display text-lg font-semibold text-ink-950 dark:text-ivory-50">{item.title}</h3>
                <div className="mt-3 flex flex-col gap-2 text-sm text-ink-600 dark:text-ink-200">
                  <span className="flex items-center gap-2">
                    <CalendarDays className="h-4 w-4 text-blue-500" aria-hidden="true" /> {item.date}
                  </span>
                  {item.host && (
                    <span className="flex items-center gap-2">
                      <User2 className="h-4 w-4 text-blue-500" aria-hidden="true" /> Hosted by {item.host}
                    </span>
                  )}
                  {item[metaKey] && (
                    <span className="flex items-center gap-2">
                      <MetaIcon className="h-4 w-4 text-blue-500" aria-hidden="true" /> {item[metaKey]}
                    </span>
                  )}
                </div>
              </div>
              <Button to="/#contact" variant="outline" size="sm" icon={ArrowRight} className="mt-6 w-fit">
                Reserve My Spot
              </Button>
            </motion.div>
          </Reveal>
        ))}
      </div>
    </Section>
  );
}
