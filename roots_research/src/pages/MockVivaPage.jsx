import { Mic } from "lucide-react";
import PageHero from "../components/layout/PageHero";
import SessionList from "../components/sections/SessionList";
import CTABanner from "../components/sections/CTABanner";
import { mockVivaSessions } from "../data/sessions";

export default function MockVivaPage() {
  return (
    <>
      <PageHero
        eyebrow="Mock Viva"
        eyebrowIcon={Mic}
        title="Rehearse Your Defence Before It Counts"
        description="Panel-style mock vivas with examiner-grade questioning, live feedback and a recorded session you can review afterward."
        tone="ink"
      />
      <SessionList items={mockVivaSessions} metaKey="seats" />
      <CTABanner
        title="Don't Walk Into Your Viva Unprepared"
        description="Book a mock viva session with a panel matched to your research area."
        ctaLabel="View All Sessions"
      />
    </>
  );
}
