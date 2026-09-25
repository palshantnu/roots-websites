import { Route } from "lucide-react";
import Section from "../ui/Section";
import SectionHeading from "../ui/SectionHeading";
import Reveal from "../ui/Reveal";
import { useSection } from "../../hooks/useApi";

export default function ProcessTimeline() {
  const { items: processSteps } = useSection("process_steps");

  return (
    <Section>
      <SectionHeading
        eyebrow="How It Works"
        eyebrowIcon={Route}
        title="A Clear Path From Draft to Defence"
        description="No guesswork, no vague timelines — every engagement follows the same transparent four-step process."
      />

      <div className="relative mt-16 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
        <div className="absolute left-0 right-0 top-6 hidden h-px bg-blue-300/40 lg:block" aria-hidden="true" />
        {processSteps.map((item, i) => (
          <Reveal key={item.id} delay={i * 0.12} className="relative flex flex-col items-center text-center lg:items-start lg:text-left">
            <span className="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-blue font-display text-lg font-semibold text-white shadow-lg shadow-blue-500/25">
              {String(i + 1).padStart(2, "0")}
            </span>
            <h3 className="mt-4 font-display text-lg font-semibold text-ink-950 dark:text-ivory-50">{item.title}</h3>
            <p className="mt-2 text-sm leading-relaxed text-ink-600 dark:text-ink-200">{item.description}</p>
          </Reveal>
        ))}
      </div>
    </Section>
  );
}
