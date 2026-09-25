/**
 * Helpers for admin-managed text.
 */

/**
 * Parses an article body written in the admin ("## " heading, "### "
 * sub-heading, "- " list items, blank line between paragraphs) into the
 * block format <BlogPost> renders: [{ type: 'p' | 'h2' | 'h3', text }, { type: 'ul', items }].
 */
export function parseBody(body = '') {
  return String(body ?? '')
    .replace(/\r\n/g, '\n')
    .split(/\n{2,}/)
    .map((chunk) => chunk.trim())
    .filter(Boolean)
    .map((chunk) => {
      const lines = chunk.split('\n').map((l) => l.trim());
      if (lines.every((l) => l.startsWith('- '))) {
        return { type: 'ul', items: lines.map((l) => l.slice(2).trim()) };
      }
      if (chunk.startsWith('### ')) return { type: 'h3', text: chunk.slice(4).trim() };
      if (chunk.startsWith('## ')) return { type: 'h2', text: chunk.slice(3).trim() };
      return { type: 'p', text: lines.join(' ') };
    });
}

/** Replaces {tokens} in admin text, e.g. fillTokens('{years} years', { years: 5 }). */
export function fillTokens(text = '', values = {}) {
  return String(text ?? '').replace(/\{(\w+)\}/g, (match, key) => (key in values ? values[key] : match));
}

/** Splits admin text on blank lines into paragraphs. */
export function paragraphs(text = '') {
  return String(text ?? '')
    .split(/\n{2,}/)
    .map((p) => p.trim())
    .filter(Boolean);
}

/** Numeric counter value from an admin-entered string such as "2,400". */
export function toNumber(value) {
  return Number(String(value ?? '').replace(/[^\d.]/g, '')) || 0;
}
