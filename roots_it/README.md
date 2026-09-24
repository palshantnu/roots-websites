# Roots Technology — Corporate Website

Modern, responsive marketing site for **Roots Technology**, a software development
and digital marketing agency. Built with React + Vite and engineered to become a
dynamic (API/CMS-backed) site with no component rewrites.

## Tech stack

- **React 18** + **Vite**
- **react-router-dom v6** — lazy-loaded routes, SEO-friendly URLs
- **Framer Motion** — subtle scroll reveals & counters
- **react-helmet-async** — per-page `<SEO>` (title, description, OG, canonical)
- **react-icons** — behind a string → component `iconMap` so data stays serializable
- **Design-token CSS** — `src/styles/*`

## Getting started

```bash
npm install
npm run dev      # http://localhost:5173
npm run build
npm run preview
```

## Architecture

All content lives in **`src/data/*.js`** as plain arrays/objects shaped like API
responses. Components never hardcode copy. To go dynamic:

```js
// today
import { services } from '../data/services';

// tomorrow
const services = await fetch(`${import.meta.env.VITE_API_BASE_URL}/services`).then(r => r.json());
```

The object shapes (id, slug, title, description, icon, image, …) already match a
typical CMS collection.

### Key folders

| Path | Purpose |
|---|---|
| `src/data` | Single source of truth for all content |
| `src/components/common` | Design-system primitives (Button, SEO, Reveal, FAQ…) |
| `src/components/layout` | Navbar, Footer, Layout shell |
| `src/components/sections` | Reusable page sections (CTA, Stats, Process…) |
| `src/components/templates` | `ServiceDetailTemplate` powers all 9 service pages |
| `src/pages` | Route components (thin — compose sections) |
| `src/styles` | `globals` (tokens/utilities), `layout`, `components`, `pages` |

## Routes

```
/                                 Home
/about                            About Us
/services                         All services
/services/website-development
/services/software-development
/services/mobile-app-development
/services/custom-software
/services/ecommerce-solutions
/digital-marketing
/digital-marketing/seo
/digital-marketing/social-media-marketing
/digital-marketing/paid-advertising
/portfolio                        Portfolio (filterable)
/case-studies                     Case studies
/blog                             Blog listing
/blog/:slug                       Blog post
/contact                          Contact
*                                 404
```

## Responsiveness

Mobile-first. Breakpoints at 480 / 768 / 1024 / 1280px. Special care: mobile
navigation drawer, service/portfolio grids, contact form, footer.

© 2026 Roots Technology. All Rights Reserved.
