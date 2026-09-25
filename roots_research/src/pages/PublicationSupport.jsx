import { BookMarked } from "lucide-react";
import PageHero from "../components/layout/PageHero";
import OfferingsGrid from "../components/sections/OfferingsGrid";
import Testimonials from "../components/sections/Testimonials";
import CTABanner from "../components/sections/CTABanner";
import { usePage, useSection } from "../hooks/useApi";

export default function PublicationSupport() {
  const { page } = usePage("publication-support");
  const { items: publicationOfferings } = useSection("publication_offerings");

  return (
    <>
      <PageHero page={page} eyebrowIcon={BookMarked} />
      <OfferingsGrid
        eyebrow="What We Handle"
        eyebrowIcon={BookMarked}
        title="Everything Between Thesis and Publication"
        description="Publishing has its own rules — we make sure your manuscript meets every one of them before it reaches an editor's desk."
        offerings={publicationOfferings}
      />
      <Testimonials />
      <CTABanner cta={page?.cta} />
    </>
  );
}
