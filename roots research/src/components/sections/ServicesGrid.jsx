import { motion } from "framer-motion";
import { ArrowUpRight, Layers } from "lucide-react";
import { Link } from "react-router-dom";
import Section from "../ui/Section";
import SectionHeading from "../ui/SectionHeading";
import Reveal from "../ui/Reveal";
import Card from "../ui/Card";
import { services } from "../../data/services";

const iconTone = {
  ink: "bg-white border border-blue-200 text-blue-600",
  blue: "bg-gradient-blue text-white",
};

export default function ServicesGrid() {
  return (
    <Section id="services">
      <SectionHeading
        eyebrow="What We Offer"
        eyebrowIcon={Layers}
        title="Everything Your Thesis Needs, In One Place"
        description="From the first proposal draft to your final publication, choose exactly the level of support that fits how you work."
      />

      <div className="mt-14 grid grid-cols-1 gap-6 sm:grid-cols-2">
        {services.map((service, i) => (
          <Reveal key={service.title} delay={i * 0.1}>
            <Card glow={service.tone} className="h-full">
              <motion.div
                whileHover={{ rotate: -6, scale: 1.08 }}
                className={`flex h-14 w-14 items-center justify-center rounded-2xl shadow-lg ${iconTone[service.tone]}`}
              >
                <service.icon className="h-6 w-6" aria-hidden="true" />
              </motion.div>
              <h3 className="mt-6 font-display text-xl font-semibold text-ink-950 sm:text-2xl dark:text-ivory-50">
                {service.title}
              </h3>
              <p className="mt-3 text-sm leading-relaxed text-ink-600 sm:text-base dark:text-ink-200">
                {service.description}
              </p>
              <Link
                to={service.href}
                className="focus-ring mt-6 inline-flex items-center gap-1.5 text-sm font-semibold text-ink-800 transition-colors hover:text-blue-600 dark:text-ivory-100 dark:hover:text-blue-300"
              >
                Learn More
                <ArrowUpRight className="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1 group-hover:-translate-y-1" />
              </Link>
            </Card>
          </Reveal>
        ))}
      </div>
    </Section>
  );
}
