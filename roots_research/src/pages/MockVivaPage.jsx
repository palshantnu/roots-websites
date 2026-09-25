import { Mic } from "lucide-react";
import PageHero from "../components/layout/PageHero";
import SessionList from "../components/sections/SessionList";
import CTABanner from "../components/sections/CTABanner";
import { usePage, useSection } from "../hooks/useApi";

export default function MockVivaPage() {
  const { page } = usePage("mock-viva");
  const { items: mockVivaSessions } = useSection("mock_viva_sessions");

  return (
    <>
      <PageHero page={page} eyebrowIcon={Mic} tone="ink" />
      <SessionList items={mockVivaSessions} />
      <CTABanner cta={page?.cta} />
    </>
  );
}
