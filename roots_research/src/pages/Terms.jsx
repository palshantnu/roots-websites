import { FileText } from "lucide-react";
import LegalPage from "../components/layout/LegalPage";

const sections = [
  {
    heading: "1. Nature of Our Services",
    body: "ThesisCraft Academy provides academic mentoring, research guidance, editing and consultation services. We do not guarantee grades, admission decisions or publication acceptance, which remain at the discretion of your institution or target journal.",
  },
  {
    heading: "2. Academic Integrity",
    body: "Our services are intended to support and develop your own research and writing skills. You remain responsible for ensuring your final submission complies with your institution's academic integrity policies.",
  },
  {
    heading: "3. Engagement & Payment",
    body: "Scope, pricing and timelines are agreed in writing before work begins. Payment milestones are tied to deliverables, and any changes to scope will be quoted separately before proceeding.",
  },
  {
    heading: "4. Revisions",
    body: "Unlimited revisions are included within the agreed scope for up to 30 days after final delivery. Requests outside the original scope may be quoted as an additional service.",
  },
  {
    heading: "5. Plagiarism-Free Guarantee",
    body: "Every written deliverable is checked against Turnitin or iThenticate prior to delivery. If a substantiated plagiarism issue is found within the delivered material, we will correct it at no additional cost.",
  },
  {
    heading: "6. Cancellations & Refunds",
    body: "Cancellations made before work begins on a milestone are eligible for a full refund of that milestone. Partial refunds may apply once work is underway, calculated pro-rata to completed work.",
  },
  {
    heading: "7. Intellectual Property",
    body: "Upon full payment, all rights to the delivered material transfer to you. ThesisCraft Academy retains no ownership claim over your finished work.",
  },
  {
    heading: "8. Limitation of Liability",
    body: "ThesisCraft Academy is not liable for academic outcomes, institutional decisions or third-party actions beyond the direct scope of the services we provide.",
  },
  {
    heading: "9. Governing Law",
    body: "These terms are governed by the laws of the jurisdiction in which ThesisCraft Academy is registered, without regard to conflict-of-law principles.",
  },
];

export default function Terms() {
  return (
    <LegalPage
      eyebrow="Terms of Service"
      eyebrowIcon={FileText}
      title="Clear Terms, No Fine-Print Surprises"
      description="The terms that govern your engagement with ThesisCraft Academy."
      updated="March 1, 2026"
      sections={sections}
    />
  );
}
