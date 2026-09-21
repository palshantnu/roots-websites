import Hero from "../components/sections/Hero";
import AIResearchBanner from "../components/sections/AIResearchBanner";
import ServicesGrid from "../components/sections/ServicesGrid";
import Divider from "../components/ui/Divider";
import WhyChooseUs from "../components/sections/WhyChooseUs";
import ProgramsSection from "../components/sections/ProgramsSection";
import FAQAccordion from "../components/sections/FAQAccordion";
import BlogPreview from "../components/sections/BlogPreview";
import Testimonials from "../components/sections/Testimonials";
import GlobalPresence from "../components/sections/GlobalPresence";
import LocationLinks from "../components/sections/LocationLinks";
import ContactForm from "../components/sections/ContactForm";

export default function Home() {
  return (
    <>
      <Hero />
      <AIResearchBanner />
      <ServicesGrid />
      <Divider />
      <WhyChooseUs />
      <ProgramsSection />
      <FAQAccordion />
      <BlogPreview />
      <Divider />
      <Testimonials />
      <GlobalPresence />
      <LocationLinks />
      <ContactForm />
    </>
  );
}
