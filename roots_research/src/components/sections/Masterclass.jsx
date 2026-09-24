import { motion } from "framer-motion";
import { PlayCircle, CalendarDays, Users } from "lucide-react";
import Button from "../ui/Button";

export default function Masterclass() {
  return (
    <motion.div
      whileHover={{ y: -6 }}
      className="relative flex h-full flex-col overflow-hidden rounded-[2rem] bg-gradient-blue p-8 text-white shadow-xl shadow-blue-500/25 sm:p-10"
    >
      <div className="pointer-events-none absolute -top-10 -right-10 h-48 w-48 rounded-full bg-white/15 blur-2xl animate-drift-slow" aria-hidden="true" />
      <div className="pointer-events-none absolute -bottom-16 -left-10 h-52 w-52 rounded-full bg-white/10 blur-2xl animate-drift" aria-hidden="true" />

      <span className="relative flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-blue-600">
        <PlayCircle className="h-7 w-7" aria-hidden="true" />
      </span>

      <h3 className="relative mt-6 font-display text-2xl font-semibold sm:text-3xl">
        Live Masterclasses &amp; Research Workshops
      </h3>
      <p className="relative mt-3 max-w-md text-sm leading-relaxed text-white/85 sm:text-base">
        Weekly sessions on methodology design, academic writing craft and tool
        mastery (SPSS, R, citation managers) — led by mentors who've supervised
        100+ theses each.
      </p>

      <div className="relative mt-6 flex flex-wrap gap-4 text-xs font-semibold text-white/85 sm:text-sm">
        <span className="flex items-center gap-1.5">
          <CalendarDays className="h-4 w-4" aria-hidden="true" /> Every Saturday
        </span>
        <span className="flex items-center gap-1.5">
          <Users className="h-4 w-4" aria-hidden="true" /> Limited to 30 seats
        </span>
      </div>

      <Button
        href="#contact"
        variant="outline"
        className="relative mt-8 w-fit !border-white/40 !bg-white/10 !text-white hover:!bg-white/20"
      >
        Reserve Your Seat
      </Button>
    </motion.div>
  );
}
