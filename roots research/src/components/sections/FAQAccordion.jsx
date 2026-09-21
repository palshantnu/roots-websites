import { useId, useState } from "react";
import { AnimatePresence, motion } from "framer-motion";
import { HelpCircle, Plus } from "lucide-react";
import Section from "../ui/Section";
import SectionHeading from "../ui/SectionHeading";
import Reveal from "../ui/Reveal";
import { faqs } from "../../data/faqs";

function FAQItem({ faq, isOpen, onToggle, index }) {
  const panelId = useId();
  return (
    <Reveal delay={Math.min(index * 0.05, 0.4)}>
      <div
        className={`overflow-hidden rounded-2xl border transition-colors duration-300 ${
          isOpen
            ? "border-blue-300 bg-blue-50/60 dark:border-blue-400/30 dark:bg-blue-400/10"
            : "border-ink-900/10 bg-white hover:border-blue-200 dark:border-white/10 dark:bg-white/[0.03] dark:hover:border-blue-400/20"
        }`}
      >
        <h3>
          <button
            type="button"
            onClick={onToggle}
            aria-expanded={isOpen}
            aria-controls={panelId}
            className="focus-ring flex w-full items-center justify-between gap-4 px-5 py-4 text-left sm:px-6 sm:py-5"
          >
            <span className="text-sm font-semibold text-ink-800 sm:text-base dark:text-ivory-100">
              {faq.question}
            </span>
            <motion.span
              animate={{ rotate: isOpen ? 135 : 0 }}
              transition={{ duration: 0.3 }}
              className={`flex h-8 w-8 shrink-0 items-center justify-center rounded-full ${
                isOpen ? "bg-gradient-blue text-white" : "bg-ink-900/5 text-ink-500 dark:bg-white/10 dark:text-ivory-200"
              }`}
            >
              <Plus className="h-4 w-4" aria-hidden="true" />
            </motion.span>
          </button>
        </h3>
        <AnimatePresence initial={false}>
          {isOpen && (
            <motion.div
              id={panelId}
              key="content"
              initial={{ height: 0, opacity: 0 }}
              animate={{ height: "auto", opacity: 1 }}
              exit={{ height: 0, opacity: 0 }}
              transition={{ duration: 0.3, ease: [0.22, 1, 0.36, 1] }}
              className="overflow-hidden"
            >
              <p className="px-5 pb-5 text-sm leading-relaxed text-ink-600 sm:px-6 sm:text-base dark:text-ink-200">
                {faq.answer}
              </p>
            </motion.div>
          )}
        </AnimatePresence>
      </div>
    </Reveal>
  );
}

export default function FAQAccordion() {
  const [openIndex, setOpenIndex] = useState(0);

  return (
    <Section className="bg-ivory-100/60 dark:bg-ink-900/20">
      <SectionHeading
        eyebrow="Frequently Asked"
        eyebrowIcon={HelpCircle}
        tone="ink"
        title="Answers Before You Ask"
        description="Everything scholars typically want to know before starting — if your question isn't here, our team is one message away."
      />

      <div className="mx-auto mt-14 flex max-w-3xl flex-col gap-3">
        {faqs.map((faq, i) => (
          <FAQItem
            key={faq.question}
            faq={faq}
            index={i}
            isOpen={openIndex === i}
            onToggle={() => setOpenIndex((cur) => (cur === i ? -1 : i))}
          />
        ))}
      </div>
    </Section>
  );
}
