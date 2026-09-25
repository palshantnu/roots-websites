import { FlaskConical } from "lucide-react";
import PageHero from "../components/layout/PageHero";
import OfferingsGrid from "../components/sections/OfferingsGrid";
import AIResearchBanner from "../components/sections/AIResearchBanner";
import ProcessTimeline from "../components/sections/ProcessTimeline";
import CTABanner from "../components/sections/CTABanner";
import { usePage, useSection } from "../hooks/useApi";

export default function ResearchSupport() {
  const { page } = usePage("research-support");
  const { items: researchOfferings } = useSection("research_offerings");

  return (
    <>
      <PageHero page={page} eyebrowIcon={FlaskConical} />
      <OfferingsGrid
        eyebrow="Core Offerings"
        title="Research Support, Chapter by Chapter"
        description="Targeted help wherever you're stuck — from your very first research question to a fully validated methodology."
        offerings={researchOfferings}
      />
      <AIResearchBanner />
      <ProcessTimeline />
      <CTABanner cta={page?.cta} />
    </>
  );
}
