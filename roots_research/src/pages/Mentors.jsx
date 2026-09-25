import { Users2 } from "lucide-react";
import PageHero from "../components/layout/PageHero";
import WhyChooseUs from "../components/sections/WhyChooseUs";
import CTABanner from "../components/sections/CTABanner";
import { usePage } from "../hooks/useApi";

export default function Mentors() {
  const { page } = usePage("mentors");

  return (
    <>
      <PageHero page={page} eyebrowIcon={Users2} />
      <WhyChooseUs />
      <CTABanner cta={page?.cta} />
    </>
  );
}
