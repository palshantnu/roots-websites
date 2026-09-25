import { FiAlertTriangle, FiInbox, FiRefreshCw } from 'react-icons/fi';

/**
 * Loading / error / empty handling for API-driven sections. Renders the
 * children only once there is something to show.
 *
 *   <AsyncState loading={loading} error={error} empty={!items.length} onRetry={reload}
 *               emptyText="No projects yet.">
 *     ...grid...
 *   </AsyncState>
 */
export default function AsyncState({
  loading,
  error,
  empty = false,
  onRetry,
  emptyText = 'Nothing to show here yet — check back soon.',
  errorText = 'We couldn’t load this section right now.',
  children,
}) {
  if (loading && empty) {
    return (
      <div className="async-state" role="status" aria-live="polite">
        <span className="spinner" />
        <span className="visually-hidden">Loading…</span>
      </div>
    );
  }

  if (error && empty) {
    return (
      <div className="async-state async-state--error" role="alert">
        <FiAlertTriangle aria-hidden="true" />
        <p>{errorText}</p>
        {onRetry && (
          <button type="button" className="btn btn--outline btn--sm" onClick={onRetry}>
            <FiRefreshCw /> Try again
          </button>
        )}
      </div>
    );
  }

  if (empty) {
    return (
      <div className="async-state">
        <FiInbox aria-hidden="true" />
        <p>{emptyText}</p>
      </div>
    );
  }

  return children;
}
