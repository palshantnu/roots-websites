import { useEffect } from 'react';

/** Prevent background scroll while `locked` is true (drawers, modals). */
export function useLockBodyScroll(locked) {
  useEffect(() => {
    if (!locked) return;
    const original = document.body.style.overflow;
    document.body.style.overflow = 'hidden';
    return () => {
      document.body.style.overflow = original;
    };
  }, [locked]);
}

export default useLockBodyScroll;
