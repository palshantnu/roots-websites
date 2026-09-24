import AnimatedCounter from '../common/AnimatedCounter';

/** Animated statistic. `stat` = { value, suffix, label } */
export default function StatCard({ stat }) {
  return (
    <div className="stat">
      <div className="stat__value">
        <AnimatedCounter value={stat.value} suffix={stat.suffix} />
      </div>
      <p className="stat__label">{stat.label}</p>
    </div>
  );
}
