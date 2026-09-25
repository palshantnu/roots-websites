import { motion } from "framer-motion";
import { ArrowRight, Compass, Sparkles } from "lucide-react";
import Button from "../ui/Button";
import Badge from "../ui/Badge";
import GradientBlobs from "../ui/GradientBlobs";
import FloatingIcons from "../ui/FloatingIcons";
import AnimatedCounter from "../ui/AnimatedCounter";
import HighlightedTitle from "../ui/HighlightedTitle";
import { usePage, useSection } from "../../hooks/useApi";
import { toNumber } from "../../lib/format";

export default function Hero() {
  const { page } = usePage("home");
  const { items: stats } = useSection("hero_stats");

  return (
    <section className="relative isolate overflow-hidden pt-36 pb-24 sm:pt-40 sm:pb-28">
      <div className="absolute inset-0 -z-20 bg-gradient-to-b from-ivory-100 via-white to-ivory-100 dark:from-ink-950 dark:via-ink-950 dark:to-ink-900" />
      <GradientBlobs />
      <FloatingIcons />

      <div className="relative mx-auto flex max-w-4xl flex-col items-center px-4 text-center sm:px-6 lg:px-8">
        <motion.div
          initial={{ opacity: 0, y: 16 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.6 }}
        >
          {page?.eyebrow && (
            <Badge tone="solid" icon={Sparkles}>
              {page.eyebrow}
            </Badge>
          )}
        </motion.div>

        <motion.h1
          initial={{ opacity: 0, y: 24 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.7, delay: 0.1 }}
          className="mt-6 font-display text-4xl font-semibold leading-[1.1] tracking-tight text-ink-950 sm:text-5xl md:text-6xl dark:text-ivory-50 text-balance"
        >
          <HighlightedTitle title={page?.title} highlight={page?.highlight} />
        </motion.h1>

        <motion.p
          initial={{ opacity: 0, y: 24 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.7, delay: 0.2 }}
          className="mt-6 max-w-2xl text-base leading-relaxed text-ink-600 sm:text-lg dark:text-ink-200 text-balance"
        >
          {page?.description}
        </motion.p>

        <motion.div
          initial={{ opacity: 0, y: 24 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.7, delay: 0.3 }}
          className="mt-9 flex flex-col gap-4 sm:flex-row"
        >
          <Button to="/#contact" size="lg" icon={ArrowRight}>
            Begin Your Research
          </Button>
          <Button to="/#services" size="lg" variant="outline" icon={Compass} iconPosition="left">
            Explore Services
          </Button>
        </motion.div>

        <motion.div
          initial={{ opacity: 0, y: 30 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.7, delay: 0.45 }}
          className="mt-16 grid w-full grid-cols-2 gap-4 sm:grid-cols-4 sm:gap-6"
        >
          {stats.map((stat, i) => (
            <div
              key={stat.id}
              className="rounded-2xl border border-ink-900/10 bg-white/70 px-3 py-5 shadow-lg shadow-ink-900/5 backdrop-blur-md dark:border-white/10 dark:bg-white/[0.04]"
            >
              <p className="font-display text-2xl font-semibold text-gradient-blue sm:text-3xl">
                <AnimatedCounter value={toNumber(stat.value)} suffix={stat.suffix ?? ""} duration={2 + i * 0.15} />
              </p>
              <p className="mt-1 text-xs font-medium text-ink-500 sm:text-sm dark:text-ink-300">
                {stat.title}
              </p>
            </div>
          ))}
        </motion.div>
      </div>
    </section>
  );
}
