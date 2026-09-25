import { motion } from "framer-motion";
import { Sparkles } from "lucide-react";
import Reveal from "../ui/Reveal";
import Badge from "../ui/Badge";
import Button from "../ui/Button";
import AnimatedCounter from "../ui/AnimatedCounter";
import { useSection } from "../../hooks/useApi";
import { getIcon } from "../../lib/icons";
import { toNumber } from "../../lib/format";

export default function AIResearchBanner() {
  const { items: tags } = useSection("ai_tags");
  const { items: stats } = useSection("ai_stats");

  return (
    <section className="relative overflow-hidden py-20 md:py-28">
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <Reveal className="relative overflow-hidden rounded-[2.5rem] border-2 border-blue-200 bg-gradient-paper px-6 py-14 shadow-2xl shadow-blue-500/10 dark:border-white/10 dark:bg-gradient-ink dark:shadow-ink-950/30 sm:px-12 sm:py-20">
          <div
            className="pointer-events-none absolute inset-0 opacity-[0.06]"
            style={{
              backgroundImage:
                "linear-gradient(rgba(58,93,137,0.6) 1px, transparent 1px), linear-gradient(90deg, rgba(58,93,137,0.6) 1px, transparent 1px)",
              backgroundSize: "42px 42px",
            }}
            aria-hidden="true"
          />
          <div className="pointer-events-none absolute -top-32 right-0 h-96 w-96 rounded-full bg-blue-300/25 blur-3xl animate-mesh" aria-hidden="true" />
          <div className="pointer-events-none absolute -bottom-24 -left-16 h-80 w-80 rounded-full bg-blue-200/25 blur-3xl animate-mesh [animation-delay:6s]" aria-hidden="true" />

          <div className="relative flex flex-col items-center gap-6 text-center">
            <Badge tone="blue" icon={Sparkles}>
              New &middot; AI-Powered Research Assistant
            </Badge>
            <h2 className="font-display max-w-3xl text-3xl font-semibold text-ink-950 dark:text-ivory-50 sm:text-4xl md:text-5xl text-balance">
              Research at the speed of thought,{" "}
              <span className="text-gradient-blue italic">guided by real experts</span>
            </h2>
            <p className="max-w-2xl text-base text-ink-600 dark:text-ink-200 sm:text-lg text-balance">
              Our in-house AI assistant scans millions of academic papers to
              surface gaps, map existing literature and flag originality
              concerns — then a human mentor reviews every insight before it
              reaches you.
            </p>

            <div className="mt-4 flex flex-wrap items-center justify-center gap-3">
              {tags.map((tag, i) => {
                const TagIcon = getIcon(tag.icon);
                return (
                <motion.span
                  key={tag.id}
                  initial={{ opacity: 0, y: 10 }}
                  whileInView={{ opacity: 1, y: 0 }}
                  viewport={{ once: true }}
                  transition={{ delay: i * 0.08, duration: 0.4 }}
                  whileHover={{ y: -3, scale: 1.04 }}
                  className="flex items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-4 py-2 text-xs font-semibold text-ink-800 backdrop-blur-md dark:border-white/10 dark:bg-white/5 dark:text-ivory-50 sm:text-sm"
                >
                  <TagIcon className="h-4 w-4 text-blue-600 dark:text-blue-300" aria-hidden="true" />
                  {tag.title}
                </motion.span>
                );
              })}
            </div>

            <Button to="/#contact" variant="blue" size="lg" icon={Sparkles} iconPosition="left" className="mt-4">
              Try the Research Assistant
            </Button>

            <div className="mt-10 grid w-full grid-cols-2 gap-4 border-t border-ink-900/10 pt-10 dark:border-white/10 sm:grid-cols-4 sm:gap-6">
              {stats.map((stat) => {
                const StatIcon = getIcon(stat.icon);
                return (
                  <div key={stat.id} className="flex flex-col items-center gap-2">
                    <span className="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 dark:bg-white/5 dark:text-blue-300">
                      <StatIcon className="h-5 w-5" aria-hidden="true" />
                    </span>
                    <p className="font-display text-xl font-semibold text-ink-950 dark:text-ivory-50 sm:text-2xl">
                      {stat.meta ? stat.meta : <AnimatedCounter value={toNumber(stat.value)} suffix={stat.suffix ?? ""} />}
                    </p>
                    <p className="text-xs font-medium text-ink-500 dark:text-ink-300 sm:text-sm">{stat.title}</p>
                  </div>
                );
              })}
            </div>
          </div>
        </Reveal>
      </div>
    </section>
  );
}
