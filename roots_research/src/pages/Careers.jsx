import { Briefcase, MapPin, Clock, ArrowRight } from "lucide-react";
import PageHero from "../components/layout/PageHero";
import Section from "../components/ui/Section";
import SectionHeading from "../components/ui/SectionHeading";
import Reveal from "../components/ui/Reveal";
import Button from "../components/ui/Button";
import CTABanner from "../components/sections/CTABanner";
import { usePage, useSection } from "../hooks/useApi";

export default function Careers() {
  const { page } = usePage("careers");
  const { items: roles } = useSection("careers_openings");

  return (
    <>
      <PageHero page={page} eyebrowIcon={Briefcase} />

      <Section>
        <SectionHeading
          align="left"
          eyebrow="Open Roles"
          title="Current Openings"
          description="Don't see a fit? Send us your CV anyway — we're always expanding our verified mentor network."
        />
        <div className="mt-10 flex flex-col gap-4">
          {roles.map((role, i) => (
            <Reveal key={role.id} delay={Math.min(i * 0.06, 0.3)}>
              <div className="flex flex-col gap-4 rounded-2xl border border-ink-900/10 bg-white p-5 shadow-sm transition hover:border-blue-300 hover:shadow-md sm:flex-row sm:items-center sm:justify-between dark:border-white/10 dark:bg-white/[0.04]">
                <div>
                  <h3 className="font-display text-base font-semibold text-ink-950 dark:text-ivory-50">{role.title}</h3>
                  <div className="mt-1.5 flex flex-wrap gap-x-4 gap-y-1 text-xs text-ink-500 dark:text-ink-300">
                    <span className="flex items-center gap-1.5">
                      <Clock className="h-3.5 w-3.5" aria-hidden="true" /> {role.subtitle}
                    </span>
                    <span className="flex items-center gap-1.5">
                      <MapPin className="h-3.5 w-3.5" aria-hidden="true" /> {role.meta}
                    </span>
                  </div>
                </div>
                <Button to="/#contact" variant="outline" size="sm" icon={ArrowRight} className="w-fit shrink-0">
                  Apply Now
                </Button>
              </div>
            </Reveal>
          ))}
        </div>
      </Section>

      <CTABanner cta={page?.cta} />
    </>
  );
}
