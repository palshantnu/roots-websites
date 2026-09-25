import { PlayCircle } from "lucide-react";
import PageHero from "../components/layout/PageHero";
import SessionList from "../components/sections/SessionList";
import CTABanner from "../components/sections/CTABanner";
import { usePage, useSection } from "../hooks/useApi";

export default function WorkshopsPage() {
  const { page } = usePage("workshops");
  const { items: workshops } = useSection("workshops");

  return (
    <>
      <PageHero page={page} eyebrowIcon={PlayCircle} tone="blue" />
      <SessionList items={workshops} metaIsHost />
      <CTABanner cta={page?.cta} />
    </>
  );
}
