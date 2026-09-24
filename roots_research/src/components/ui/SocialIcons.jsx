// lucide-react no longer ships brand/social marks, so these are small
// hand-rolled SVGs (currentColor fill) matching each platform's glyph.

export function FacebookIcon({ className = "h-4 w-4" }) {
  return (
    <svg viewBox="0 0 24 24" fill="currentColor" className={className} aria-hidden="true">
      <path d="M13.5 21v-7.5h2.5l.4-3H13.5V8.4c0-.87.24-1.46 1.5-1.46h1.6V4.28C16.3 4.19 15.3 4.1 14.2 4.1c-2.45 0-4.13 1.49-4.13 4.24v2.16H7.6v3h2.47V21h3.43Z" />
    </svg>
  );
}

export function TwitterIcon({ className = "h-4 w-4" }) {
  return (
    <svg viewBox="0 0 24 24" fill="currentColor" className={className} aria-hidden="true">
      <path d="M18.24 3h3.03l-6.62 7.56L22.5 21h-6.1l-4.77-6.24L6.14 21H3.1l7.08-8.09L2.25 3h6.26l4.31 5.7L18.24 3Zm-1.06 16.17h1.68L7.9 4.74H6.1l11.08 14.43Z" />
    </svg>
  );
}

export function InstagramIcon({ className = "h-4 w-4" }) {
  return (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className={className} aria-hidden="true">
      <rect x="3" y="3" width="18" height="18" rx="5" />
      <circle cx="12" cy="12" r="4" />
      <circle cx="17.2" cy="6.8" r="1" fill="currentColor" stroke="none" />
    </svg>
  );
}

export function LinkedinIcon({ className = "h-4 w-4" }) {
  return (
    <svg viewBox="0 0 24 24" fill="currentColor" className={className} aria-hidden="true">
      <path d="M6.94 5.5a1.94 1.94 0 1 1-3.88 0 1.94 1.94 0 0 1 3.88 0ZM3.5 8.75h3.4V20.5h-3.4V8.75Zm6.1 0h3.26v1.6h.05c.45-.86 1.56-1.77 3.22-1.77 3.44 0 4.07 2.27 4.07 5.21v6.71h-3.4v-5.95c0-1.42-.03-3.24-1.98-3.24-1.98 0-2.29 1.55-2.29 3.14v6.05h-3.4V8.75Z" />
    </svg>
  );
}
