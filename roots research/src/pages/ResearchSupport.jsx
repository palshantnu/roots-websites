import { FlaskConical } from "lucide-react";
import PageHero from "../components/layout/PageHero";
import OfferingsGrid from "../components/sections/OfferingsGrid";
import AIResearchBanner from "../components/sections/AIResearchBanner";
import ProcessTimeline from "../components/sections/ProcessTimeline";
import CTABanner from "../components/sections/CTABanner";
import { researchOfferings } from "../data/researchSupport";

export default function ResearchSupport() {
  return (
    <>
      <PageHero
        eyebrow="Research Support"
        eyebrowIcon={FlaskConical}
        title="From Blank Page to Defensible Research Design"
        description="Every strong thesis starts with a strong research foundation — we help you build one that survives committee scrutiny."
      />
      <OfferingsGrid
        eyebrow="Core Offerings"
        title="Research Support, Chapter by Chapter"
        description="Targeted help wherever you're stuck — from your very first research question to a fully validated methodology."
        offerings={researchOfferings}
      />
      <AIResearchBanner />
      <ProcessTimeline />
      <CTABanner
        title="Stuck on Your Research Design?"
        description="Get a free 20-minute consultation with a mentor in your subject area."
        ctaLabel="Book a Free Consultation"
      />
    </>
  );
}
