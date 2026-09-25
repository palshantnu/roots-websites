import { motion } from "framer-motion";
import GradientBlobs from "../ui/GradientBlobs";
import FloatingIcons from "../ui/FloatingIcons";
import Badge from "../ui/Badge";
import Divider from "../ui/Divider";
import HighlightedTitle from "../ui/HighlightedTitle";

/**
 * Page header. Pass `page` (from usePage) to render the admin-managed copy,
 * or the individual props directly.
 */
export default function PageHero({ page, eyebrowIcon, tone = "blue", ...props }) {
  const eyebrow = props.eyebrow ?? page?.eyebrow;
  const title = props.title ?? page?.title;
  const description = props.description ?? page?.description;

  return (
    <section className="relative isolate overflow-hidden pt-36 pb-12 sm:pt-40 sm:pb-16">
      <div className="absolute inset-0 -z-20 bg-gradient-to-b from-ivory-100 via-white to-white dark:from-ink-950 dark:via-ink-950 dark:to-ink-950" />
      <GradientBlobs />
      <FloatingIcons />
      <div className="relative mx-auto flex max-w-3xl flex-col items-center gap-5 px-4 text-center sm:px-6 lg:px-8">
        {eyebrow && (
          <motion.div initial={{ opacity: 0, y: 10 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: 0.5 }}>
            <Badge tone={tone} icon={eyebrowIcon}>
              {eyebrow}
            </Badge>
          </motion.div>
        )}
        <motion.h1
          initial={{ opacity: 0, y: 18 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.6, delay: 0.1 }}
          className="font-display text-4xl font-semibold leading-tight tracking-tight text-ink-950 sm:text-5xl dark:text-ivory-50 text-balance"
        >
          <HighlightedTitle title={title} highlight={page?.highlight} />
        </motion.h1>
        {description && (
          <motion.p
            initial={{ opacity: 0, y: 18 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.2 }}
            className="max-w-2xl text-base leading-relaxed text-ink-600 sm:text-lg dark:text-ink-200 text-balance"
          >
            {description}
          </motion.p>
        )}
        <Divider className="mt-1" />
      </div>
    </section>
  );
}
