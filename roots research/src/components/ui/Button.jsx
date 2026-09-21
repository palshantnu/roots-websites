import { useState } from "react";
import { motion } from "framer-motion";
import { Link } from "react-router-dom";

const variants = {
  primary:
    "text-ink-950 bg-white border-2 border-ink-900/12 shadow-lg shadow-blue-500/10 hover:border-blue-400 hover:shadow-xl hover:shadow-blue-500/20 dark:bg-ink-900 dark:text-ivory-50 dark:border-white/10 dark:hover:border-blue-400",
  blue:
    "text-white bg-gradient-blue shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/40",
  outline:
    "border border-ink-900/25 text-ink-900 dark:text-ivory-100 dark:border-ivory-100/25 hover:border-blue-500 hover:text-blue-600 dark:hover:text-blue-300 bg-white/50 dark:bg-white/[0.03] backdrop-blur",
  ghost:
    "text-ink-800 dark:text-ivory-200 hover:text-blue-600 dark:hover:text-blue-300",
};

const shimmerColor = {
  primary: "rgba(58, 93, 137, 0.3)",
  blue: "rgba(255, 255, 255, 0.5)",
};

const sizes = {
  sm: "px-4 py-2 text-sm",
  md: "px-6 py-3 text-sm md:text-base",
  lg: "px-8 py-4 text-base md:text-lg",
};

const shimmerVariants = new Set(["primary", "blue"]);

/**
 * Shared button component: hairline/filled variants in the 2-color (ink +
 * blue) system, with a diagonal shimmer sweep on hover and a click ripple.
 */
export default function Button({
  children,
  variant = "primary",
  size = "md",
  to,
  href,
  icon: Icon,
  iconPosition = "right",
  className = "",
  onClick,
  type = "button",
  ...props
}) {
  const [ripples, setRipples] = useState([]);

  const addRipple = (e) => {
    const rect = e.currentTarget.getBoundingClientRect();
    const rippleSize = Math.max(rect.width, rect.height) * 1.4;
    const x = e.clientX - rect.left - rippleSize / 2;
    const y = e.clientY - rect.top - rippleSize / 2;
    const id = Date.now() + Math.random();
    setRipples((r) => [...r, { id, x, y, rippleSize }]);
    setTimeout(() => setRipples((r) => r.filter((rp) => rp.id !== id)), 650);
  };

  const handleClick = (e) => {
    addRipple(e);
    onClick?.(e);
  };

  const classes = `group relative overflow-hidden inline-flex items-center justify-center gap-2 rounded-full font-semibold tracking-tight transition-all duration-300 focus-ring disabled:opacity-50 disabled:pointer-events-none cursor-pointer ${variants[variant]} ${sizes[size]} ${className}`;

  const rippleColor = variant === "blue" ? "bg-white/30" : "bg-blue-200/50";
  const rippleLayer = ripples.map((r) => (
    <motion.span
      key={r.id}
      initial={{ scale: 0, opacity: 0.55 }}
      animate={{ scale: 1, opacity: 0 }}
      transition={{ duration: 0.65, ease: "easeOut" }}
      className={`pointer-events-none absolute rounded-full ${rippleColor}`}
      style={{ left: r.x, top: r.y, width: r.rippleSize, height: r.rippleSize }}
    />
  ));

  const content = (
    <>
      {shimmerVariants.has(variant) && (
        <span className="btn-shimmer" style={{ "--shimmer-color": shimmerColor[variant] }} aria-hidden="true" />
      )}
      {Icon && iconPosition === "left" && (
        <Icon className="relative z-10 h-4 w-4 transition-transform duration-300 group-hover:-translate-x-0.5" aria-hidden="true" />
      )}
      <span className="relative z-10">{children}</span>
      {Icon && iconPosition === "right" && (
        <Icon className="relative z-10 h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" aria-hidden="true" />
      )}
      {rippleLayer}
    </>
  );

  const motionProps = {
    whileTap: { scale: 0.95 },
    whileHover: { scale: 1.02, y: -2 },
    transition: { type: "spring", stiffness: 400, damping: 20 },
  };

  if (to) {
    return (
      <motion.div {...motionProps} className="inline-block">
        <Link to={to} className={classes} onClick={handleClick} {...props}>
          {content}
        </Link>
      </motion.div>
    );
  }

  if (href) {
    return (
      <motion.a {...motionProps} href={href} className={classes} onClick={handleClick} {...props}>
        {content}
      </motion.a>
    );
  }

  return (
    <motion.button {...motionProps} type={type} className={classes} onClick={handleClick} {...props}>
      {content}
    </motion.button>
  );
}
