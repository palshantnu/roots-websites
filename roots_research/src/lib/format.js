/** "Mar 14, 2026" from an ISO date string. */
export function formatDate(value) {
  if (!value) return "";
  const date = new Date(value.length === 10 ? `${value}T00:00:00` : value);
  return date.toLocaleDateString("en-US", { month: "short", day: "2-digit", year: "numeric" });
}

/** "March 1, 2026" from an ISO date string. */
export function formatLongDate(value) {
  if (!value) return "";
  return new Date(value).toLocaleDateString("en-US", { month: "long", day: "numeric", year: "numeric" });
}

/** "1.2 MB" from a byte count. */
export function formatFileSize(bytes) {
  if (!bytes) return "";
  const units = ["B", "KB", "MB", "GB"];
  const exponent = Math.min(Math.floor(Math.log(bytes) / Math.log(1024)), units.length - 1);
  const size = bytes / 1024 ** exponent;
  return `${size.toFixed(size >= 10 || exponent === 0 ? 0 : 1)} ${units[exponent]}`;
}

/** Numeric counter value from an admin-entered string such as "2,400". */
export function toNumber(value) {
  return Number(String(value ?? "").replace(/[^\d.]/g, "")) || 0;
}
