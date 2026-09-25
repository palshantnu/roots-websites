import { Layers } from "lucide-react";
import PageHero from "../components/layout/PageHero";
import ServicesGrid from "../components/sections/ServicesGrid";
import ProcessTimeline from "../components/sections/ProcessTimeline";
import WhyChooseUs from "../components/sections/WhyChooseUs";
import CTABanner from "../components/sections/CTABanner";
import { usePage } from "../hooks/useApi";

export default function Services() {
  const { page } = usePage("services");

  return (
    <>
      <PageHero page={page} eyebrowIcon={Layers} />
      <ServicesGrid />
      <ProcessTimeline />
      <WhyChooseUs />
      <CTABanner cta={page?.cta} />
    </>
  );
}
