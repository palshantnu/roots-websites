import PageHero from "./PageHero";
import Section from "../ui/Section";
import Reveal from "../ui/Reveal";
import { usePage, useSection } from "../../hooks/useApi";
import { formatLongDate } from "../../lib/format";

/**
 * A policy page. Copy comes from the admin: the hero from `pages/{slug}` and
 * the numbered sections from the `sectionKey` section list. "Last updated"
 * is the date the page was last edited in the admin panel.
 */
export default function LegalPage({ slug, sectionKey, eyebrowIcon }) {
  const { page } = usePage(slug);
  const { items: sections } = useSection(sectionKey);

  return (
    <>
      <PageHero page={page} eyebrowIcon={eyebrowIcon} tone="ink" />
      <Section containerClassName="!max-w-3xl">
        {page?.updatedAt && (
          <Reveal className="mb-10 text-sm font-medium text-ink-500 dark:text-ink-300">
            Last updated: {formatLongDate(page.updatedAt)}
          </Reveal>
        )}
        <div className="flex flex-col gap-10">
          {sections.map((section, i) => (
            <Reveal key={section.id} delay={Math.min(i * 0.05, 0.3)}>
              <h2 className="font-display text-xl font-semibold text-ink-950 dark:text-ivory-50">{section.title}</h2>
              <p className="mt-3 text-sm leading-relaxed text-ink-600 sm:text-base dark:text-ink-200">
                {section.description}
              </p>
            </Reveal>
          ))}
        </div>
      </Section>
    </>
  );
}
