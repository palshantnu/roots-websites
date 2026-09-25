import { FileText } from "lucide-react";
import LegalPage from "../components/layout/LegalPage";

export default function Terms() {
  return <LegalPage slug="terms" sectionKey="terms_sections" eyebrowIcon={FileText} />;
}
