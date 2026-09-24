const tones = {
  blue: "bg-blue-50 text-blue-700 border-blue-300/60 dark:bg-blue-400/10 dark:text-blue-300 dark:border-blue-400/20",
  ink: "bg-ink-900/[0.04] text-ink-800 border-ink-900/15 dark:bg-white/5 dark:text-ivory-100 dark:border-white/10",
  outline: "bg-transparent text-ink-700 border-ink-900/20 dark:text-ivory-200 dark:border-white/15",
  solid: "bg-white/80 text-blue-700 border-blue-300/50 backdrop-blur dark:bg-white/90 dark:text-blue-700",
};

export default function Badge({ children, tone = "blue", icon: Icon, className = "" }) {
  return (
    <span
      className={`inline-flex items-center gap-1.5 rounded-full border px-3.5 py-1.5 text-xs md:text-sm font-semibold tracking-wide ${tones[tone]} ${className}`}
    >
      {Icon && <Icon className="h-3.5 w-3.5" aria-hidden="true" />}
      {children}
    </span>
  );
}
