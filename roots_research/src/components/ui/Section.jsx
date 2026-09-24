export default function Section({ children, id, className = "", containerClassName = "" }) {
  return (
    <section id={id} className={`relative overflow-hidden py-20 md:py-28 ${className}`}>
      <div className={`relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 ${containerClassName}`}>
        {children}
      </div>
    </section>
  );
}
