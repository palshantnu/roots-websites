import { ArrowRight, Sparkles } from "lucide-react";
import Section from "../ui/Section";
import Reveal from "../ui/Reveal";
import Button from "../ui/Button";

/**
 * Call-to-action banner. `cta` is the page's admin-managed banner copy
 * ({ title, description, label }); empty fields fall back to the defaults.
 */
export default function CTABanner({
  cta,
  title = "Ready to Move Your Research Forward?",
  description = "Tell us about your thesis and get matched with a mentor within 24 hours.",
  ctaLabel = "Begin Your Research",
  ctaTo = "/#contact",
}) {
  title = cta?.title || title;
  description = cta?.description || description;
  ctaLabel = cta?.label || ctaLabel;

  return (
    <Section containerClassName="!max-w-6xl">
      <Reveal className="relative overflow-hidden rounded-[2.5rem] border-2 border-blue-200 bg-gradient-paper px-6 py-14 text-center shadow-2xl shadow-blue-500/10 dark:border-white/10 dark:bg-gradient-ink dark:shadow-ink-950/30 sm:px-16 sm:py-16">
        <div className="pointer-events-none absolute -top-16 -right-10 h-64 w-64 rounded-full bg-blue-300/25 blur-3xl animate-mesh dark:bg-blue-400/15" aria-hidden="true" />
        <div className="pointer-events-none absolute -bottom-16 -left-10 h-64 w-64 rounded-full bg-blue-200/25 blur-3xl animate-mesh [animation-delay:5s] dark:bg-blue-200/10" aria-hidden="true" />
        <Sparkles className="relative mx-auto h-8 w-8 text-blue-500 dark:text-blue-300" aria-hidden="true" />
        <h2 className="relative mt-4 font-display text-2xl font-semibold text-ink-950 dark:text-ivory-50 sm:text-3xl md:text-4xl text-balance">
          {title}
        </h2>
        <p className="relative mx-auto mt-3 max-w-xl text-sm text-ink-600 dark:text-ink-200 sm:text-base text-balance">
          {description}
        </p>
        <div className="relative mt-8">
          <Button to={ctaTo} variant="blue" size="lg" icon={ArrowRight}>
            {ctaLabel}
          </Button>
        </div>
      </Reveal>
    </Section>
  );
}
