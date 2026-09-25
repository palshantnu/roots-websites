import { Lock } from "lucide-react";
import LegalPage from "../components/layout/LegalPage";

export default function Privacy() {
  return <LegalPage slug="privacy" sectionKey="privacy_sections" eyebrowIcon={Lock} />;
}
