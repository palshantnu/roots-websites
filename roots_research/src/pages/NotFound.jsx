import { motion } from "framer-motion";
import { Home, Compass, SearchX } from "lucide-react";
import GradientBlobs from "../components/ui/GradientBlobs";
import FloatingIcons from "../components/ui/FloatingIcons";
import Button from "../components/ui/Button";

export default function NotFound() {
  return (
    <section className="relative isolate flex min-h-screen items-center justify-center overflow-hidden px-4 pt-24 pb-16 text-center">
      <div className="absolute inset-0 -z-20 bg-gradient-to-b from-ivory-100 via-white to-ivory-100 dark:from-ink-950 dark:via-ink-950 dark:to-ink-900" />
      <GradientBlobs />
      <FloatingIcons />

      <div className="relative flex flex-col items-center">
        <motion.span
          initial={{ scale: 0.8, opacity: 0 }}
          animate={{ scale: 1, opacity: 1 }}
          transition={{ type: "spring", stiffness: 200, damping: 14 }}
          className="flex h-20 w-20 items-center justify-center rounded-3xl border-2 border-blue-200 bg-white text-blue-600 shadow-xl shadow-blue-500/10"
        >
          <SearchX className="h-9 w-9" aria-hidden="true" />
        </motion.span>
        <motion.h1
          initial={{ opacity: 0, y: 16 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.15 }}
          className="mt-8 font-display text-6xl font-semibold text-gradient-blue sm:text-8xl"
        >
          404
        </motion.h1>
        <motion.p
          initial={{ opacity: 0, y: 16 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.25 }}
          className="mt-4 max-w-md text-base text-ink-600 sm:text-lg dark:text-ink-200"
        >
          This page must still be in peer review — we couldn't find it. Let's get
          you back to something that exists.
        </motion.p>
        <motion.div
          initial={{ opacity: 0, y: 16 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.35 }}
          className="mt-8 flex flex-col gap-4 sm:flex-row"
        >
          <Button to="/" icon={Home} iconPosition="left">
            Back to Home
          </Button>
          <Button to="/services" variant="outline" icon={Compass} iconPosition="left">
            Explore Services
          </Button>
        </motion.div>
      </div>
    </section>
  );
}
