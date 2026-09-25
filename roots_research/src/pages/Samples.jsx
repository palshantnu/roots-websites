import { AlertTriangle, FileText, FolderOpen, GraduationCap, Library, RefreshCw, ScrollText } from "lucide-react";
import PageHero from "../components/layout/PageHero";
import Section from "../components/ui/Section";
import SectionHeading from "../components/ui/SectionHeading";
import Reveal from "../components/ui/Reveal";
import Button from "../components/ui/Button";
import SampleCard from "../components/ui/SampleCard";
import CTABanner from "../components/sections/CTABanner";
import { usePage, useSamples } from "../hooks/useApi";

// The three sample categories, matching App\Enums\SampleType in the backend.
const categories = [
  {
    type: "research_paper",
    anchor: "research-papers",
    label: "Research Papers",
    icon: FileText,
    title: "Research Paper Samples",
    description: "Journal-style papers with abstracts, methodology and results — formatted to the standards indexed journals expect.",
  },
  {
    type: "thesis",
    anchor: "thesis",
    label: "Thesis",
    icon: GraduationCap,
    title: "Thesis Samples",
    description: "Thesis chapters that show the structure, literature review depth and citation quality our mentors deliver.",
  },
  {
    type: "synopsis",
    anchor: "synopsis",
    label: "Synopsis",
    icon: ScrollText,
    title: "Synopsis Samples",
    description: "Committee-ready synopses with a clear problem statement, objectives and methodology outline.",
  },
];

function SkeletonCard() {
  return (
    <div className="h-full animate-pulse rounded-3xl border border-ink-900/10 bg-white/90 p-6 md:p-8 dark:border-white/10 dark:bg-white/[0.04]" aria-hidden="true">
      <div className="h-44 rounded-2xl bg-ink-900/5 dark:bg-white/5" />
      <div className="mt-5 h-5 w-3/4 rounded-full bg-ink-900/10 dark:bg-white/10" />
      <div className="mt-3 h-3.5 w-full rounded-full bg-ink-900/5 dark:bg-white/5" />
      <div className="mt-2 h-3.5 w-5/6 rounded-full bg-ink-900/5 dark:bg-white/5" />
      <div className="mt-6 h-9 w-32 rounded-full bg-ink-900/5 dark:bg-white/5" />
    </div>
  );
}

function EmptyCategory({ label }) {
  return (
    <Reveal className="flex flex-col items-center gap-3 rounded-3xl border-2 border-dashed border-blue-200 bg-white/60 px-6 py-12 text-center dark:border-white/10 dark:bg-white/[0.02]">
      <FolderOpen className="h-8 w-8 text-blue-400" aria-hidden="true" />
      <p className="font-display text-lg font-semibold text-ink-950 dark:text-ivory-50">No {label.toLowerCase()} samples yet</p>
      <p className="max-w-md text-sm text-ink-600 dark:text-ink-200">
        We&apos;re preparing new samples for this category. Get in touch and a mentor can share relevant examples with you.
      </p>
    </Reveal>
  );
}

function LoadError({ onRetry }) {
  return (
    <Section>
      <Reveal
        role="alert"
        className="mx-auto flex max-w-xl flex-col items-center gap-4 rounded-3xl border-2 border-blue-200 bg-gradient-paper px-6 py-12 text-center shadow-xl shadow-blue-500/10 dark:border-white/10 dark:bg-gradient-ink"
      >
        <span className="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-blue-600 shadow-md dark:bg-white/10 dark:text-blue-300">
          <AlertTriangle className="h-6 w-6" aria-hidden="true" />
        </span>
        <h2 className="font-display text-2xl font-semibold text-ink-950 dark:text-ivory-50">We couldn&apos;t load the samples</h2>
        <p className="text-sm text-ink-600 dark:text-ink-200">Please check your connection and try again in a moment.</p>
        <Button onClick={onRetry} variant="blue" icon={RefreshCw} iconPosition="left">
          Try Again
        </Button>
      </Reveal>
    </Section>
  );
}

export default function Samples() {
  const { page } = usePage("samples");
  const { data: samples, loading, error, reload } = useSamples();

  return (
    <>
      <PageHero page={page} eyebrowIcon={Library} />

      <nav aria-label="Sample categories" className="mx-auto mt-4 flex max-w-7xl flex-wrap justify-center gap-2.5 px-4 sm:px-6 lg:px-8">
        {categories.map((category) => {
          const count = samples.filter((sample) => sample.type === category.type).length;
          return (
            <a
              key={category.type}
              href={`#${category.anchor}`}
              className="focus-ring flex items-center gap-2 rounded-full border border-ink-900/15 bg-white px-4 py-2 text-xs font-semibold text-ink-700 transition-colors hover:border-blue-400 hover:text-blue-700 sm:text-sm dark:border-white/10 dark:bg-white/5 dark:text-ink-200 dark:hover:text-blue-300"
            >
              <category.icon className="h-4 w-4 text-blue-500" aria-hidden="true" />
              {category.label}
              {!loading && !error && (
                <span className="rounded-full bg-blue-50 px-2 py-0.5 text-xs text-blue-700 dark:bg-blue-400/10 dark:text-blue-200">{count}</span>
              )}
            </a>
          );
        })}
      </nav>

      {error ? (
        <LoadError onRetry={reload} />
      ) : (
        categories.map((category, index) => {
          const items = samples.filter((sample) => sample.type === category.type);
          return (
            <Section
              key={category.type}
              id={category.anchor}
              className={`scroll-mt-20 ${index % 2 === 1 ? "bg-ivory-100/60 dark:bg-ink-900/20" : ""}`}
            >
              <SectionHeading
                align="left"
                eyebrow={category.label}
                eyebrowIcon={category.icon}
                tone={index % 2 === 1 ? "ink" : "blue"}
                title={category.title}
                description={category.description}
              />
              <div className="mt-12" aria-busy={loading}>
                {loading ? (
                  <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <SkeletonCard />
                    <SkeletonCard />
                    <SkeletonCard />
                  </div>
                ) : items.length === 0 ? (
                  <EmptyCategory label={category.label} />
                ) : (
                  <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    {items.map((sample, i) => (
                      <Reveal key={sample.id} delay={Math.min(i * 0.08, 0.4)}>
                        <SampleCard sample={sample} tone={i % 2 ? "blue" : "ink"} />
                      </Reveal>
                    ))}
                  </div>
                )}
              </div>
            </Section>
          );
        })
      )}

      <CTABanner cta={page?.cta} />
    </>
  );
}
