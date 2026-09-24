import { useState } from 'react';
import { AnimatePresence, motion } from 'framer-motion';
import { FiPlus } from 'react-icons/fi';
import { classNames } from '../../utils/helpers';

/**
 * Accessible accordion. `items` is [{ q, a }].
 * One panel open at a time; first can be open via `defaultOpen`.
 */
export default function FAQ({ items = [], defaultOpen = -1 }) {
  const [open, setOpen] = useState(defaultOpen);

  return (
    <div className="faq">
      {items.map((item, i) => {
        const isOpen = open === i;
        const panelId = `faq-panel-${i}`;
        const btnId = `faq-btn-${i}`;
        return (
          <div
            key={item.q}
            className={classNames('faq__item', isOpen && 'is-open')}
          >
            <h3 style={{ margin: 0 }}>
              <button
                id={btnId}
                className="faq__q"
                aria-expanded={isOpen}
                aria-controls={panelId}
                onClick={() => setOpen(isOpen ? -1 : i)}
              >
                <span>{item.q}</span>
                <FiPlus className="faq__icon" />
              </button>
            </h3>
            <AnimatePresence initial={false}>
              {isOpen && (
                <motion.div
                  id={panelId}
                  role="region"
                  aria-labelledby={btnId}
                  className="faq__a"
                  initial={{ height: 0, opacity: 0 }}
                  animate={{ height: 'auto', opacity: 1 }}
                  exit={{ height: 0, opacity: 0 }}
                  transition={{ duration: 0.28, ease: [0.22, 1, 0.36, 1] }}
                >
                  <p className="faq__a-inner">{item.a}</p>
                </motion.div>
              )}
            </AnimatePresence>
          </div>
        );
      })}
    </div>
  );
}
