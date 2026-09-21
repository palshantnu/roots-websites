# ThesisCraft Academy

A complete, production-ready marketing website for an elite academic
thesis-writing and research-mentoring service. Built with React, React
Router, Tailwind CSS and Framer Motion — a premium, two-color (white/ink + blue)
visual system, richly animated, fully responsive, and organized into clean,
reusable components.

> **Rebranding:** "ThesisCraft Academy" is a placeholder brand name. Search
> the project for `ThesisCraft Academy` (and the shorter `ThesisCraft`) to
> swap in your final name — it appears in [`index.html`](index.html), the
> [Navbar](src/components/layout/Navbar.jsx) and [Footer](src/components/layout/Footer.jsx).

## Tech Stack

- **React 19** — functional components + hooks
- **React Router 7** — multi-page client-side routing
- **Tailwind CSS 4** — utility-first styling (via `@tailwindcss/vite`, no separate config file needed)
- **Framer Motion** — scroll-reveal, hover, parallax, carousel and page-transition animations
- **lucide-react** — icon set
- **Vite** — dev server & build tooling

## Getting Started

```bash
npm install
npm run dev
```

The dev server starts at `http://localhost:5173`.

Other scripts:

```bash
npm run build     # production build to /dist
npm run preview   # preview the production build locally
npm run lint      # run oxlint
```

## Project Structure

```
src/
  components/
    ui/         # Button, Card, Badge, Section, SectionHeading, Reveal,
                 # AnimatedCounter, GradientBlobs, FloatingIcons, Divider,
                 # ArticleCard, SocialIcons
    layout/     # Navbar, Footer, PageHero, LegalPage, ScrollToTop
    sections/   # One component per homepage section (Hero, ServicesGrid,
                 # FAQAccordion, ContactForm, GlobalPresence, etc.)
  pages/        # One component per route (Home, Services, Mentors, Blog,
                 # legal pages, 404, ...)
  data/         # Static content arrays (services, faqs, testimonials,
                 # mentors, cities, blog posts, etc.) — edit these to change
                 # copy without touching component code
  context/      # ThemeContext (dark mode) and ToastContext (form feedback)
  index.css     # Tailwind import + design tokens (ink/blue palette, keyframes)
```

## Design System

- **Palette:** a disciplined two-color system — **white/ivory** as the
  primary surface (with **ink**, `ink-50`…`ink-950`, reserved for text,
  hairline borders and dark-mode surfaces) and a steel **blue** accent
  (`blue-50`…`blue-950`, built around `#3A5D89`) as the single accent color.
  Solid "brand" fills (buttons, badges, icon chips, feature panels, the
  footer) are white with a blue border/glow rather than a dark fill; every
  gradient stays within its own family (white↔ivory or blue↔blue) — no
  unrelated hues are mixed in. Defined as custom Tailwind tokens in
  [`index.css`](src/index.css) so the whole palette can be retinted from one
  place — see `--color-ink-*` and `--color-blue-*` in the `@theme` block.
- **Type:** Fraunces (serif, display/headings) paired with Inter (sans,
  body/UI) — loaded in [`index.html`](index.html).
- **Texture:** a fixed, ultra-low-opacity SVG-noise overlay across the whole
  app (`.noise-overlay` in `index.css`) for a print-like grain, plus
  glassmorphism navbar/cards and hairline borders throughout.
- **Motion:** scroll-triggered reveal animations (`Reveal.jsx`), animated
  stat counters, a glassmorphism sticky navbar with an animated blue
  underline on the active nav link, scroll-linked parallax drift on the
  hero's floating icons (`useScroll`/`useTransform` in `FloatingIcons.jsx`),
  card hover lift + blue border-glow, a diagonal shimmer sweep on filled
  buttons, a ripple micro-interaction on click, and thin animated blue
  section dividers (`Divider.jsx`).
- **Dark mode:** toggle in the navbar (desktop) and mobile drawer; preference
  is persisted to `localStorage` and respects the OS preference on first
  visit. Dark mode intentionally swaps the large white panels back to a deep
  ink surface (via `dark:` variants and the `.bg-gradient-ink` utility) so it
  reads as a proper dark theme rather than white panels on a dark page; the
  logo mark and small accent chips deliberately stay white/blue in both
  modes for consistent brand recognition.
- **No auth:** there is no login/signup flow anywhere in this site by design
  — the navbar's only CTA is "Get Free Consultation".

## Functional Notes

- The contact form uses local React state with inline validation and a toast
  confirmation on submit — there is no backend wired up, so submissions are
  logged to the console (`console.log`) only.
- The FAQ accordion, city-region filter and "show more" location list are
  all driven by local component state — no external data fetching.
- Update the content in `src/data/*.js` to change copy, add cities, add FAQs,
  add mentors, etc. without touching any component markup. Most data items
  carry a `tone: "ink" | "blue"` field that drives which half of the palette
  their icon/card uses — alternate them for visual rhythm.

## A CSS gotcha worth knowing if you extend this

Several sections layer decorative backgrounds (`GradientBlobs`,
`FloatingIcons`) behind their content using `-z-10`/`-z-20`. A `position:
relative` container **without** an explicit `z-index` does not establish a
new stacking context, so negative-z-index children can escape past it and
render behind unrelated ancestors instead of staying local to that section.
Every section that uses negative z-index decoration therefore also carries
an `isolate` class (`isolation: isolate`) so its background layers stay
contained. If you add a new decorative background layer to a section, add
`isolate` to that section's root element too.

## Routes

| Path | Page |
| --- | --- |
| `/` | Home (all marketing sections) |
| `/services` | Services overview |
| `/research-support` | Research support offerings |
| `/publication-support` | Publication support offerings |
| `/mentors` | Mentor network |
| `/blog` | Article listing |
| `/mock-viva` | Mock viva sessions |
| `/workshops` | Masterclasses & workshops |
| `/about`, `/careers`, `/privacy`, `/terms` | Company pages |
