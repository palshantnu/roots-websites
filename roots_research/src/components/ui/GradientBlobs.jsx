/**
 * Slow-moving gradient mesh backdrop in the ink/blue palette. Absolutely
 * positioned — the parent element needs `relative overflow-hidden`.
 */
export default function GradientBlobs({ className = "", dark = false }) {
  const blueOpacity = dark ? "opacity-25" : "opacity-40";
  const deepOpacity = dark ? "opacity-30" : "opacity-[0.14]";
  return (
    <div className={`pointer-events-none absolute inset-0 -z-10 ${className}`} aria-hidden="true">
      <div
        className={`absolute -top-32 -right-16 h-[28rem] w-[28rem] rounded-full bg-blue-300 blur-[110px] ${blueOpacity} animate-mesh`}
      />
      <div
        className={`absolute -bottom-24 -left-20 h-[24rem] w-[24rem] rounded-full bg-blue-600 blur-[110px] ${deepOpacity} animate-mesh [animation-delay:5s]`}
      />
      <div
        className={`absolute top-1/3 left-1/2 h-72 w-72 -translate-x-1/2 rounded-full bg-blue-200 blur-[100px] ${blueOpacity} animate-mesh [animation-delay:9s]`}
      />
    </div>
  );
}
