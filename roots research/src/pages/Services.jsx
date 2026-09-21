import { Layers } from "lucide-react";
import PageHero from "../components/layout/PageHero";
import ServicesGrid from "../components/sections/ServicesGrid";
import ProcessTimeline from "../components/sections/ProcessTimeline";
import WhyChooseUs from "../components/sections/WhyChooseUs";
import CTABanner from "../components/sections/CTABanner";

export default function Services() {
  return (
    <>
      <PageHero
        eyebrow="Our Services"
        eyebrowIcon={Layers}
        title="Thesis Support, Built Around How You Work"
        description="Choose full-scope guidance or targeted help on a single chapter — every service is scoped, priced and scheduled before we begin."
      />
      <ServicesGrid />
      <ProcessTimeline />
      <WhyChooseUs />
      <CTABanner />
    </>
  );
}
