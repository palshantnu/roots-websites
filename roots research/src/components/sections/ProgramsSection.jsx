import { CalendarClock } from "lucide-react";
import Section from "../ui/Section";
import SectionHeading from "../ui/SectionHeading";
import Reveal from "../ui/Reveal";
import Masterclass from "./Masterclass";
import MockViva from "./MockViva";

export default function ProgramsSection() {
  return (
    <Section id="programs">
      <SectionHeading
        eyebrow="Live Programs"
        eyebrowIcon={CalendarClock}
        title="Learn Live, Practice Live"
        description="Structured group learning and one-on-one rehearsal, designed to make your independent research skills — and your defence — genuinely stronger."
      />
      <div className="mt-14 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <Reveal delay={0.1}>
          <Masterclass />
        </Reveal>
        <Reveal delay={0.2}>
          <MockViva />
        </Reveal>
      </div>
    </Section>
  );
}
