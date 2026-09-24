import { PlayCircle } from "lucide-react";
import PageHero from "../components/layout/PageHero";
import SessionList from "../components/sections/SessionList";
import CTABanner from "../components/sections/CTABanner";
import { workshops } from "../data/sessions";

export default function WorkshopsPage() {
  return (
    <>
      <PageHero
        eyebrow="Masterclasses & Workshops"
        eyebrowIcon={PlayCircle}
        title="Live Sessions to Sharpen Your Research Craft"
        description="Small-group, mentor-led workshops on the skills that make theses stronger — methodology, tools and academic writing."
        tone="blue"
      />
      <SessionList items={workshops} metaKey="host" />
      <CTABanner
        title="Seats Fill Up Fast"
        description="Reserve your spot in our next live masterclass before registration closes."
        ctaLabel="Reserve Your Seat"
      />
    </>
  );
}
