import { motion } from "framer-motion";
import { Mic, ShieldQuestion, ClipboardCheck } from "lucide-react";
import Button from "../ui/Button";

export default function MockViva() {
  return (
    <motion.div
      whileHover={{ y: -6 }}
      className="relative flex h-full flex-col overflow-hidden rounded-[2rem] border-2 border-blue-200 bg-white p-8 text-ink-950 shadow-xl shadow-blue-500/10 dark:border-white/10 dark:bg-gradient-ink dark:text-ivory-50 dark:shadow-ink-950/30 sm:p-10"
    >
      <div className="pointer-events-none absolute -top-12 -left-10 h-48 w-48 rounded-full bg-blue-200/50 blur-2xl animate-drift dark:bg-blue-400/15" aria-hidden="true" />
      <div className="pointer-events-none absolute -bottom-14 -right-10 h-52 w-52 rounded-full bg-blue-100/60 blur-2xl animate-drift-slow dark:bg-blue-300/10" aria-hidden="true" />

      <span className="relative flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-blue text-white">
        <Mic className="h-7 w-7" aria-hidden="true" />
      </span>

      <h3 className="relative mt-6 font-display text-2xl font-semibold sm:text-3xl">
        Mock Viva &amp; Defence Simulations
      </h3>
      <p className="relative mt-3 max-w-md text-sm leading-relaxed text-ink-600 dark:text-ink-200 sm:text-base">
        A full panel-style rehearsal with examiner-style questioning, real-time
        feedback and a recorded session so you can review your own delivery
        before the real defence.
      </p>

      <div className="relative mt-6 flex flex-wrap gap-4 text-xs font-semibold text-ink-600 dark:text-ink-200 sm:text-sm">
        <span className="flex items-center gap-1.5">
          <ShieldQuestion className="h-4 w-4 text-blue-500" aria-hidden="true" /> Examiner-style panel
        </span>
        <span className="flex items-center gap-1.5">
          <ClipboardCheck className="h-4 w-4 text-blue-500" aria-hidden="true" /> Written feedback report
        </span>
      </div>

      <Button
        href="#contact"
        variant="outline"
        className="relative mt-8 w-fit"
      >
        View All Sessions
      </Button>
    </motion.div>
  );
}
