/**
 * Renders a title with the admin-chosen `highlight` phrase in the blue
 * italic accent style used by the hero headings.
 */
export default function HighlightedTitle({ title, highlight }) {
  if (!title) return null;
  if (!highlight || !title.includes(highlight)) return title;

  const index = title.indexOf(highlight);
  return (
    <>
      {title.slice(0, index)}
      <span className="text-gradient-blue italic">{highlight}</span>
      {title.slice(index + highlight.length)}
    </>
  );
}
