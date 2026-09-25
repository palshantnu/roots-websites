import { useSettings } from "../../hooks/useApi";

/**
 * The site name from the admin settings, with its last word in the blue
 * accent style ("ThesisCraft Academy" -> "ThesisCraft" + "Academy").
 */
export default function BrandName({ className = "" }) {
  const { settings } = useSettings();
  const words = (settings?.siteName ?? "").trim().split(/\s+/);
  const accent = words.length > 1 ? words.pop() : "";

  return (
    <span className={className}>
      {words.join(" ")} {accent && <span className="text-gradient-blue">{accent}</span>}
    </span>
  );
}
