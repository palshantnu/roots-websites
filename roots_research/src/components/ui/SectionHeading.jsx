import Reveal from "./Reveal";
import Badge from "./Badge";

export default function SectionHeading({
  eyebrow,
  eyebrowIcon,
  title,
  description,
  align = "center",
  tone = "blue",
  className = "",
}) {
  const alignment =
    align === "center" ? "items-center text-center mx-auto" : "items-start text-left";
  return (
    <Reveal className={`flex flex-col gap-4 max-w-3xl ${alignment} ${className}`}>
      {eyebrow && (
        <Badge tone={tone} icon={eyebrowIcon}>
          {eyebrow}
        </Badge>
      )}
      <h2 className="font-display text-3xl sm:text-4xl md:text-5xl font-semibold tracking-tight text-ink-950 dark:text-ivory-50 text-balance">
        {title}
      </h2>
      {description && (
        <p className="text-base md:text-lg text-ink-600 dark:text-ink-200 text-balance">
          {description}
        </p>
      )}
    </Reveal>
  );
}
