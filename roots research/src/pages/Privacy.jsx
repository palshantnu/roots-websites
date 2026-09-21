import { Lock } from "lucide-react";
import LegalPage from "../components/layout/LegalPage";

const sections = [
  {
    heading: "1. Information We Collect",
    body: "We collect only what's necessary to match you with a mentor and deliver your service — your name, contact details, academic level, subject area and the documents you choose to share with us.",
  },
  {
    heading: "2. How We Use Your Information",
    body: "Your information is used solely to coordinate mentoring, process your requests and communicate updates about your engagement. We never sell or rent your data to third parties.",
  },
  {
    heading: "3. Confidentiality of Your Research",
    body: "All research materials, drafts and personal details shared with mentors are covered under a standing non-disclosure agreement. Mentors may not reuse, publish or share your work.",
  },
  {
    heading: "4. Data Storage & Security",
    body: "Documents and communications are stored on access-controlled systems with encryption at rest and in transit. Access is limited to your assigned mentor and support team.",
  },
  {
    heading: "5. Cookies & Analytics",
    body: "Our website uses minimal, privacy-respecting analytics to understand site usage. No personally identifying data is sold to advertising networks.",
  },
  {
    heading: "6. Your Rights",
    body: "You may request a copy of your data, ask us to correct inaccuracies, or request deletion of your records at any time by contacting our support team.",
  },
  {
    heading: "7. Changes to This Policy",
    body: "We'll notify active clients of material changes to this policy via email at least 14 days before they take effect.",
  },
  {
    heading: "8. Contact Us",
    body: "Questions about this policy can be sent to hello@thesiscraftacademy.com and we'll respond within two business days.",
  },
];

export default function Privacy() {
  return (
    <LegalPage
      eyebrow="Privacy Policy"
      eyebrowIcon={Lock}
      title="Your Privacy, Protected by Design"
      description="How ThesisCraft Academy collects, uses and protects your information."
      updated="March 1, 2026"
      sections={sections}
    />
  );
}
