import { Briefcase, MapPin, Clock, ArrowRight } from "lucide-react";
import PageHero from "../components/layout/PageHero";
import Section from "../components/ui/Section";
import SectionHeading from "../components/ui/SectionHeading";
import Reveal from "../components/ui/Reveal";
import Button from "../components/ui/Button";
import CTABanner from "../components/sections/CTABanner";

const roles = [
  { title: "PhD Research Mentor — Management", type: "Contract · Remote", location: "Global" },
  { title: "PhD Research Mentor — Engineering", type: "Contract · Remote", location: "Global" },
  { title: "Statistical Analyst (SPSS/R/Python)", type: "Part-time · Remote", location: "Global" },
  { title: "Academic Content Editor", type: "Full-time · Remote", location: "India" },
  { title: "Student Success Coordinator", type: "Full-time · Hybrid", location: "Bengaluru, India" },
];

export default function Careers() {
  return (
    <>
      <PageHero
        eyebrow="Careers"
        eyebrowIcon={Briefcase}
        title="Help Scholars Do Their Best Work"
        description="We're a distributed team of researchers, editors and analysts who care about academic integrity as much as we care about deadlines."
      />

      <Section>
        <SectionHeading
          align="left"
          eyebrow="Open Roles"
          title="Current Openings"
          description="Don't see a fit? Send us your CV anyway — we're always expanding our verified mentor network."
        />
        <div className="mt-10 flex flex-col gap-4">
          {roles.map((role, i) => (
            <Reveal key={role.title} delay={Math.min(i * 0.06, 0.3)}>
              <div className="flex flex-col gap-4 rounded-2xl border border-ink-900/10 bg-white p-5 shadow-sm transition hover:border-blue-300 hover:shadow-md sm:flex-row sm:items-center sm:justify-between dark:border-white/10 dark:bg-white/[0.04]">
                <div>
                  <h3 className="font-display text-base font-semibold text-ink-950 dark:text-ivory-50">{role.title}</h3>
                  <div className="mt-1.5 flex flex-wrap gap-x-4 gap-y-1 text-xs text-ink-500 dark:text-ink-300">
                    <span className="flex items-center gap-1.5">
                      <Clock className="h-3.5 w-3.5" aria-hidden="true" /> {role.type}
                    </span>
                    <span className="flex items-center gap-1.5">
                      <MapPin className="h-3.5 w-3.5" aria-hidden="true" /> {role.location}
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

      <CTABanner
        title="Don't See the Right Role?"
        description="We're always open to hearing from published researchers and academic editors."
        ctaLabel="Send Us Your CV"
      />
    </>
  );
}
