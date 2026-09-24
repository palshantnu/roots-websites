/** Full-height spinner used as the Suspense fallback for lazy routes. */
export default function Loader({ label = 'Loading' }) {
  return (
    <div className="route-loader" role="status" aria-live="polite">
      <span className="spinner" />
      <span className="visually-hidden">{label}…</span>
    </div>
  );
}
