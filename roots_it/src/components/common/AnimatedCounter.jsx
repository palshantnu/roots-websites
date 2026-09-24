import { useCountUp } from '../../hooks/useCountUp';

/** Number that counts up from 0 when scrolled into view. */
export default function AnimatedCounter({ value, suffix = '', prefix = '', className }) {
  const [current, ref] = useCountUp(value);
  return (
    <span ref={ref} className={className}>
      {prefix}
      {current}
      {suffix}
    </span>
  );
}
