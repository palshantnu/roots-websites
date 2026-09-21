import PageHero from "./PageHero";
import Section from "../ui/Section";
import Reveal from "../ui/Reveal";

export default function LegalPage({ eyebrow, eyebrowIcon, title, description, updated, sections }) {
  return (
    <>
      <PageHero eyebrow={eyebrow} eyebrowIcon={eyebrowIcon} title={title} description={description} tone="ink" />
      <Section containerClassName="!max-w-3xl">
        <Reveal className="mb-10 text-sm font-medium text-ink-500 dark:text-ink-300">
          Last updated: {updated}
        </Reveal>
        <div className="flex flex-col gap-10">
          {sections.map((section, i) => (
            <Reveal key={section.heading} delay={Math.min(i * 0.05, 0.3)}>
              <h2 className="font-display text-xl font-semibold text-ink-950 dark:text-ivory-50">{section.heading}</h2>
              <p className="mt-3 text-sm leading-relaxed text-ink-600 sm:text-base dark:text-ink-200">
                {section.body}
              </p>
            </Reveal>
          ))}
        </div>
      </Section>
    </>
  );
}
