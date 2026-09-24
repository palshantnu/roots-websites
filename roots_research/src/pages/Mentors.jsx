import { Users2 } from "lucide-react";
import PageHero from "../components/layout/PageHero";
import WhyChooseUs from "../components/sections/WhyChooseUs";
import CTABanner from "../components/sections/CTABanner";

export default function Mentors() {
  return (
    <>
      <PageHero
        eyebrow="Our Mentors"
        eyebrowIcon={Users2}
        title="Learn From People Who've Actually Done It"
        description="Every mentor on our roster holds an advanced degree, has published in their field, and has personally guided dozens of scholars to submission."
      />
      <WhyChooseUs />
      <CTABanner
        title="Want to Mentor With Us?"
        description="We're always looking for published researchers to join our verified mentor network."
        ctaLabel="Apply to Mentor"
      />
    </>
  );
}
