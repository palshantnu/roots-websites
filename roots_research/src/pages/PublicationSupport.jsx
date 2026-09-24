import { BookMarked } from "lucide-react";
import PageHero from "../components/layout/PageHero";
import OfferingsGrid from "../components/sections/OfferingsGrid";
import Testimonials from "../components/sections/Testimonials";
import CTABanner from "../components/sections/CTABanner";
import { publicationOfferings } from "../data/publicationSupport";

export default function PublicationSupport() {
  return (
    <>
      <PageHero
        eyebrow="Publication Support"
        eyebrowIcon={BookMarked}
        title="Turn Your Thesis Into Published Research"
        description="From journal shortlisting to reviewer responses, we help your findings reach the audience they deserve."
      />
      <OfferingsGrid
        eyebrow="What We Handle"
        eyebrowIcon={BookMarked}
        title="Everything Between Thesis and Publication"
        description="Publishing has its own rules — we make sure your manuscript meets every one of them before it reaches an editor's desk."
        offerings={publicationOfferings}
      />
      <Testimonials />
      <CTABanner
        title="Ready to Publish Your Research?"
        description="Share your thesis chapter and target journal — we'll map out a submission-ready plan."
        ctaLabel="Start Publication Support"
      />
    </>
  );
}
