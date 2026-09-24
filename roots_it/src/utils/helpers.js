/** Small shared helpers. */

/** Format an ISO date string like "2026-02-14" -> "Feb 14, 2026". */
export function formatDate(iso) {
  try {
    return new Date(iso).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
    });
  } catch {
    return iso;
  }
}

/** Rough reading time from a word count. */
export function readingTime(text = '') {
  const words = String(text).trim().split(/\s+/).length;
  return `${Math.max(1, Math.round(words / 200))} min read`;
}

/** Deterministic placeholder image URL (no external asset pipeline needed). */
export function placeholderImage(seed, w = 800, h = 600) {
  return `https://picsum.photos/seed/${encodeURIComponent(seed)}/${w}/${h}`;
}

/** Deterministic avatar URL. */
export function avatarImage(seed) {
  return `https://i.pravatar.cc/160?u=${encodeURIComponent(seed)}`;
}

export function classNames(...parts) {
  return parts.filter(Boolean).join(' ');
}
