import { ArrowUpRight, User, CalendarDays } from "lucide-react";
import { Link } from "react-router-dom";
import Card from "./Card";
import { formatDate } from "../../lib/format";

const headerStyles = {
  ink: "border border-blue-200 bg-gradient-paper dark:border-white/10 dark:bg-gradient-ink",
  blue: "bg-gradient-blue",
};

export default function ArticleCard({ article, tone = "ink" }) {
  const isInk = tone !== "blue";
  return (
    <Card glow="blue" className="flex h-full flex-col">
      <div className={`h-36 w-full rounded-2xl relative overflow-hidden ${headerStyles[tone] || headerStyles.ink}`}>
        <div className="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(255,255,255,0.18),transparent_55%)]" />
        <span
          className={`absolute left-4 top-4 rounded-full px-3 py-1 text-xs font-bold backdrop-blur ${
            isInk ? "bg-blue-50 text-blue-700 dark:bg-white/10 dark:text-blue-200" : "bg-ink-950/15 text-white"
          }`}
        >
          {article.category}
        </span>
      </div>
      <h3 className="mt-5 font-display text-lg font-semibold leading-snug text-ink-950 dark:text-ivory-50">
        {article.title}
      </h3>
      <p className="mt-2.5 flex-1 text-sm leading-relaxed text-ink-600 dark:text-ink-200">{article.excerpt}</p>
      <div className="mt-5 flex items-center justify-between border-t border-ink-900/10 pt-4 text-xs text-ink-500 dark:border-white/10 dark:text-ink-300">
        <span className="flex items-center gap-1.5">
          <User className="h-3.5 w-3.5" aria-hidden="true" /> {article.author}
        </span>
        <span className="flex items-center gap-1.5">
          <CalendarDays className="h-3.5 w-3.5" aria-hidden="true" /> {formatDate(article.publishedAt)}
        </span>
      </div>
      <Link
        to="/blog"
        className="focus-ring mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-ink-800 transition-colors hover:text-blue-600 dark:text-ivory-100 dark:hover:text-blue-300"
      >
        Read Article <ArrowUpRight className="h-4 w-4" />
      </Link>
    </Card>
  );
}
