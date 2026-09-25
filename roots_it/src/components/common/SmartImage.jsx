import { useState } from 'react';
import { FiImage } from 'react-icons/fi';

/**
 * <img> that falls back to a neutral placeholder when the admin has not
 * uploaded an image yet, or the URL fails to load, so cards never break.
 */
export default function SmartImage({ src, alt = '', className, style, ...props }) {
  const [failed, setFailed] = useState(false);

  if (!src || failed) {
    return (
      <span className={`img-fallback ${className ?? ''}`} style={style} role={alt ? 'img' : undefined} aria-label={alt || undefined}>
        <FiImage aria-hidden="true" />
      </span>
    );
  }

  return <img src={src} alt={alt} className={className} style={style} onError={() => setFailed(true)} {...props} />;
}
