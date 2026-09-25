import { Download, Eye, FileText } from "lucide-react";
import Card from "./Card";
import Button from "./Button";
import { formatFileSize } from "../../lib/format";

/**
 * A downloadable sample (research paper, thesis or synopsis). Uses the cover
 * image when one is uploaded, otherwise the ArticleCard-style header.
 */
export default function SampleCard({ sample, tone = "ink" }) {
  const file = sample.file;
  const extension = file?.extension?.toUpperCase();
  const isInk = tone !== "blue";

  return (
    <Card glow="blue" className="flex h-full flex-col">
      <div
        className={`relative h-44 w-full overflow-hidden rounded-2xl ${
          sample.thumbnail
            ? "bg-ivory-100 dark:bg-ink-900"
            : isInk
              ? "border border-blue-200 bg-gradient-paper dark:border-white/10 dark:bg-gradient-ink"
              : "bg-gradient-blue"
        }`}
      >
        {sample.thumbnail ? (
          <img src={sample.thumbnail} alt="" loading="lazy" className="h-full w-full object-cover" />
        ) : (
          <>
            <div className="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(255,255,255,0.18),transparent_55%)]" />
            <FileText
              className={`absolute right-5 bottom-5 h-14 w-14 ${isInk ? "text-blue-200 dark:text-blue-400/30" : "text-white/40"}`}
              aria-hidden="true"
            />
          </>
        )}
        <span
          className={`absolute left-4 top-4 rounded-full px-3 py-1 text-xs font-bold backdrop-blur ${
            isInk || sample.thumbnail ? "bg-blue-50 text-blue-700 dark:bg-white/10 dark:text-blue-200" : "bg-ink-950/15 text-white"
          }`}
        >
          {sample.typeLabel}
        </span>
      </div>

      <h3 className="mt-5 font-display text-lg font-semibold leading-snug text-ink-950 dark:text-ivory-50">{sample.title}</h3>
      {sample.shortDescription && (
        <p className="mt-2.5 flex-1 text-sm leading-relaxed text-ink-600 dark:text-ink-200">{sample.shortDescription}</p>
      )}

      <div className="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-ink-900/10 pt-4 dark:border-white/10">
        {file ? (
          <>
            <span className="flex items-center gap-1.5 text-xs text-ink-500 dark:text-ink-300">
              <FileText className="h-3.5 w-3.5" aria-hidden="true" />
              {[extension, formatFileSize(file.size)].filter(Boolean).join(" · ")}
            </span>
            <div className="flex flex-wrap gap-2">
              {file.extension === "pdf" && (
                <Button
                  href={file.viewUrl}
                  target="_blank"
                  rel="noopener noreferrer"
                  variant="outline"
                  size="sm"
                  icon={Eye}
                  iconPosition="left"
                  aria-label={`View ${sample.title}`}
                >
                  View
                </Button>
              )}
              <Button href={file.downloadUrl} variant="blue" size="sm" icon={Download} iconPosition="left" aria-label={`Download ${sample.title}`}>
                Download
              </Button>
            </div>
          </>
        ) : (
          <span className="text-xs text-ink-500 dark:text-ink-300">Document coming soon</span>
        )}
      </div>
    </Card>
  );
}
