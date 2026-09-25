import { Info } from "lucide-react";
import PageHero from "../components/layout/PageHero";
import Section from "../components/ui/Section";
import SectionHeading from "../components/ui/SectionHeading";
import Reveal from "../components/ui/Reveal";
import AnimatedCounter from "../components/ui/AnimatedCounter";
import WhyChooseUs from "../components/sections/WhyChooseUs";
import CTABanner from "../components/sections/CTABanner";
import { usePage, useSection } from "../hooks/useApi";
import { getIcon } from "../lib/icons";
import { toNumber } from "../lib/format";

export default function About() {
  const { page } = usePage("about");
  const { items: values } = useSection("about_values");
  const { items: founderStats } = useSection("about_stats");

  return (
    <>
      <PageHero page={page} eyebrowIcon={Info} />

      <Section>
        <SectionHeading
          align="left"
          eyebrow="Our Values"
          title="What Guides Every Engagement"
          description="These aren't wall art — they're the standard every mentor is held to."
        />
        <div className="mt-12 grid grid-cols-1 gap-6 sm:grid-cols-3">
          {values.map((v, i) => {
            const ValueIcon = getIcon(v.icon);
            return (
              <Reveal key={v.id} delay={i * 0.1} className="rounded-3xl border border-ink-900/10 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/[0.04]">
                <span className="flex h-12 w-12 items-center justify-center rounded-2xl bg-white border border-blue-200 text-blue-600 shadow-md">
                  <ValueIcon className="h-5.5 w-5.5" aria-hidden="true" />
                </span>
                <h3 className="mt-4 font-display text-lg font-semibold text-ink-950 dark:text-ivory-50">{v.title}</h3>
                <p className="mt-2 text-sm leading-relaxed text-ink-600 dark:text-ink-200">{v.description}</p>
              </Reveal>
            );
          })}
        </div>

        <Reveal delay={0.2} className="mt-16 grid grid-cols-1 gap-6 rounded-[2rem] border-2 border-blue-200 bg-gradient-paper p-10 text-center shadow-xl shadow-blue-500/10 dark:border-white/10 dark:bg-gradient-ink dark:shadow-ink-950/30 sm:grid-cols-3 sm:p-14">
          {founderStats.map((stat) => (
            <div key={stat.id}>
              <p className="font-display text-3xl font-semibold text-gradient-blue sm:text-4xl">
                <AnimatedCounter value={toNumber(stat.value)} suffix={stat.suffix ?? ""} />
              </p>
              <p className="mt-1 text-sm text-ink-600 dark:text-ink-200">{stat.title}</p>
            </div>
          ))}
        </Reveal>
      </Section>

      <WhyChooseUs />
      <CTABanner cta={page?.cta} />
    </>
  );
}
