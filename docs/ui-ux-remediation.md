# NDSES UI/UX Remediation

Tracking document for the frontend remediation and redesign. Status is only **Done** after the change is implemented **and** verified in a real browser (Playwright on installed Google Chrome, plus manual screenshot review).

- Branch: `ui-redesign`
- Order: Next.js app (Vercel) first, then port the design to the WordPress theme (`wordpress/ndses-theme`, the Flywheel site).
- Browser tooling: `npm run test:e2e` (Playwright, `channel: "chrome"`). `e2e/layout-audit.spec.ts` measures every route at 320 / 375 / 390 / 430 / 768 / 1024 / 1280 / 1440 / 1920px for horizontal overflow, text that overlaps other text, clipped text, console errors, and failed requests, and saves full-page screenshots at 390 and 1440px to `test-results/screens/<phase>/`. `e2e/navigation.spec.ts` tests menu behavior.

## 1. Why earlier changes "did nothing" (root causes)

| # | Cause | Evidence |
| --- | --- | --- |
| R1 | **No overhaul was ever implemented.** | `git log --all` since 2026-10-15 shows only content, CMS, docs, and hero commits (`d30ebc0`…`88a62fc`). No branch or PR contains UI/UX overhaul work. |
| R2 | **Two separate frontends.** Vercel serves the Next.js app; Flywheel serves the WordPress theme. Changes to one never appear on the other. | Vercel Production deployment is commit `0d638db` at `ndses-m2cnoersv-seaforthstrategies.vercel.app`; `ndses.flywheelsites.com` renders `wordpress/ndses-theme` (`theme.css?ver=…`). |
| R3 | **Vercel Production lags the repo.** The truck-photo hero (PR #2) only exists as a Preview deployment. | GitHub deployments: Production `0d638db`; Previews `5743e35`, `88a62fc`. |
| R4 | **Dropdowns stick open by design.** Every menu is a native `<details>` element in a Server Component with no JavaScript, so it only toggles on click and ignores pointer-leave, outside clicks, Escape, and route changes. The header persists across client-side navigation, so an open menu stays open on the next page. | `components/layout/Header.tsx` (`<details className="nav-menu">`, `<details className="mobile-nav-panel">`). |
| R5 | **Stylesheet cancels its own fixes.** `app/globals.css` (2,052 lines) redefines the same selectors in seven media blocks and appended "override" sections, so a fix in one place is undone by a later rule. | e.g. `.hero` defined at lines 574, 1538, 1713; `.hero-meta` at 628, 1552, 1741, 1816; `.nav-menu summary` at 436 and 456. |

## 2. Route inventory

| Route | Template / key components | Phase |
| --- | --- | --- |
| `/` | Home: hero, action dock, notices, `SectionRenderer`, `DumpsterCard`, `ServiceAreaMap`, `FAQAccordion`, CTA band | B |
| `/residential` | Page hero, community cards, `ScheduleCalendar`, recycling lists, bulk items | C |
| `/residential/[communitySlug]` (5) | Page hero, schedule card, `ServiceNoticeList`, zone map, `DownloadList`, `FAQSection` | C |
| `/commercial` | Page hero, service-area map image, container cards, recycling, `FAQSection` | C |
| `/dumpster-rentals` | Page hero, `DumpsterCard` grid, materials lists, `RentalProcess`, `DumpsterGuide`, `InquiryForm` | C |
| `/dumpster-calculator` | Page hero, `DumpsterCalculator`, `DumpsterCard` grid | C |
| `/special-events` | Page hero, `SectionRenderer`, `InquiryForm` | C |
| `/about` | Page hero, Our Story, mission, `ServiceAreaMap`, team placeholder | D |
| `/contact` | Page hero, `InquiryForm`, contact card, notices | D |
| `/request-service` | Page hero (inline), `InquiryForm` | D |
| `/make-a-payment` | Page hero, `PaymentForm` (PayEngine SecureFields) | D |
| `/faqs` | Page hero, `FAQAccordion` per category | D |
| `/whats-new`, `/service-notices`, `/service-notices/[slug]` | Page hero, `ServiceNoticeList` / notice detail | D |
| `/privacy-policy`, `/terms` | Legal page hero + card | D |
| `/api/*` | Forms, payments, webhook, geocode, revalidate (no UI) | Preserve |

Shared across every route: `app/layout.tsx`, `AnnouncementBar`, `Header`, `Footer`, `ServiceNoticePopup`, `ScrollReveal`, `app/globals.css`.

## 3. Defects

Severity: **Critical** (broken interaction or unreadable content), **High** (clear visual or usability failure), **Medium**, **Low**.

| ID | Area | Defect | Severity | Status | Verified |
| --- | --- | --- | --- | --- | --- |
| G1 | Header nav | Dropdowns open only on click, never close on pointer-leave, outside click, or Escape; stay open after navigating (R4). | Critical | Done — `components/layout/Header.tsx` rebuilt as a client component (hover with grace period, click/tap toggle, Escape, outside click, arrow/Home/End keys, closes on route change, `aria-expanded`/`aria-controls`). | `e2e/navigation.spec.ts` (9 desktop tests) |
| G2 | Mobile nav | Native `<details>` sheet: no scroll lock, stays open after navigation, nested `<details>` per section, no `aria-expanded`. | Critical | Done — full-height sheet, body scroll lock, accordion buttons with `aria-expanded`, closes on navigate/Escape/resize to desktop. | `e2e/navigation.spec.ts` (4 mobile tests) |
| G3 | Global CSS | Conflicting duplicate rules across breakpoints (R5). | High | Done — `app/globals.css` rewritten as one cascade (tokens → base → layout → components), one rule per selector. | Layout audit |
| G4 | Typography | No defined type scale or brand typefaces; system font stack; headline sizes vary by page. | High | Done — Archivo (headings) + Public Sans (body) via `next/font`; fluid type scale tokens. | Screenshots |
| G5 | Footer | Plain three-column text list; no hours, no clear contact CTA, nav labels repeated without grouping. | Medium | Done — `components/layout/Footer.tsx`: call/quote band, contact with hours, Services / Customers groups, legal row. | Screenshots |
| G6 | Public copy | Developer notes shown to visitors: "Client-supplied … workbook" captions, "no Facebook integration", "Payment and ACH form placeholder", About "Team profiles coming soon". | Medium | Done — captions removed; What's New uses the existing notice copy; nav description reworded; placeholder team section removed from About (text kept in data). Legal pages still hold launch-review placeholder text (needs NDSES legal copy). | Screenshots |
| G7 | Forms | Error messages aren't linked to fields (`aria-describedby`/`aria-invalid` missing); focus styles inconsistent. | Medium | Done — Inquiry and Payment forms link errors to fields; consistent focus ring. | Code review |
| G8 | Console | PayEngine SDK logs "You must pass your Public API…" on `/make-a-payment` when no public key is configured; `/favicon.ico` 404 on every route. | Low | Favicon: Done (`app/icon.png`). PayEngine message: expected until a public key is set in env. | Layout audit |
| H1 | Home | "Why NDS" text-image section renders an empty grey `image-placeholder` box. | High | Done — `SectionRenderer` renders a real image (`image` field added to the textImage section type). | Screenshots |
| H2 | Home | Five dumpster cards squeezed into five columns at 1024–1280px. | Medium | Done — auto-fill grid + rebuilt `DumpsterCard` (no word-by-word wrapping). | Screenshots + audit |
| H3 | Home | Decorative "01 / 02 / 03" numbering on service cards that aren't a sequence. | Low | Done — replaced with service icons. | Screenshots |
| C1 | Service pages | Every inner page uses the same pale text-only page hero; no imagery or hierarchy; approved hero action buttons never rendered. | High | Done — shared `components/layout/PageHero.tsx` on all 17 routes, renders hero actions; photos on Residential and About. | Screenshots |
| C2 | Cards | Card padding, radius, and shadow differ between card types; lists inside cards unstyled. | Medium | Done — one card system; commercial container cards rebuilt; dumpster size guide rebuilt as a comparison table. | Screenshots |

| C3 | Dumpster Rentals | The same five sizes appeared twice (cards, then a "size guide" of tall narrow cards); filter used `role="tablist"` without tab panels. | Medium | Done — `DumpsterGuide` is now a comparison table with `aria-pressed` filter buttons; still hands the chosen size to the form. | Screenshots |
| C4 | Images | Content images had no `sizes`, so the browser requested 3840px variants (slow optimization, Commercial page never reached network idle in dev). | Medium | Done — `sizes` on every content image. | Audit timing |
| C5 | Map | Labels clipped at the right edge and collided for nearby communities. | Medium | Done — labels flip left near the edge or when a neighbor is close. | Layout audit |

Baseline audit (`AUDIT_PHASE=before`): no horizontal overflow or clipped text at any width; the overlap check's hits were inside closed menus (not visible) and have been excluded from the measurement; console/network issues per G8.

## 4. Phases

| Phase | Scope | Status |
| --- | --- | --- |
| A | Tokens, typography, layout, header, navigation, footer, shared interactions | Done |
| B | Homepage | Done |
| C | Residential, community, commercial, dumpster rentals, calculator, special events | Done |
| D | About, contact, request service, payment, FAQs, notices, legal | Done |
| E | Regression: all routes × widths, nav tests, typecheck/lint/unit/build | Done (Next.js) |
| WP | Port design to `wordpress/ndses-theme` | Done — live on Flywheel (theme 3.0.1) |

## 5. Verification results (Next.js, production build)

Run against `next build && next start` on installed Google Chrome:

| Check | Result |
| --- | --- |
| `e2e/layout-audit.spec.ts` — 17 routes × 9 widths (320–1920px): horizontal overflow, overlapping text, clipped text, console errors, failed requests | 17/17 passed, 0 findings |
| `e2e/navigation.spec.ts` — 9 desktop + 4 mobile interaction tests | 13/13 passed |
| `npm run typecheck` / `npm run lint` | Pass |
| `npm test` (Vitest) | 23/23 passed |
| `npm run build` | Pass |

Earlier runs of the same audit did flag real problems (map labels colliding at 768px, the favicon 404, the PayEngine console error), so a clean result is not a vacuous pass.

Screenshots: `test-results/screens/before/` (baseline) and `test-results/screens/final/` at 390px and 1440px for every route. These are generated locally and not committed.

### Not verified / still open

- **Real-device testing:** iOS Safari and Android Chrome were not tested; mobile checks used Chrome with touch emulation at phone widths.
- **WordPress-fed content:** with no `WORDPRESS_*` env vars set, the Next.js app renders its local seed content. Layouts were not checked against live CMS content.
- **Payments:** not tested end to end, since no PayEngine public key is configured locally; the form shows "Online payments are not available yet" and loads no SDK.
- **Legal copy:** Privacy Policy and Terms still contain launch-review placeholder text from NDSES.
- **Social links:** footer Facebook and Instagram links still point to the generic facebook.com and instagram.com (needs the real NDS page URLs).

## 6. WordPress theme port (theme 3.0.0)

Same design system as the Next.js app, mapped onto the theme's own class names so templates and ACF wiring are unchanged:

- `assets/css/theme.css` rewritten on the shared tokens; Archivo and Public Sans self-hosted in `assets/fonts/` (no third-party font requests).
- `header.php` rebuilt with the Next.js header structure: utility bar with office hours, dropdown navigation, phone / Get a Quote / Pay Bill, and the mobile sheet. Menu items come from the menu assigned to **Primary Navigation** in Appearance → Menus when there is one (top-level items become dropdowns, sub-items their links); otherwise from the built-in nav in `inc/data.php`, which now mirrors the Next.js menu.
- `assets/js/theme.js` `initNavigation()`: the same behavior as the Next.js header (hover with grace period, click/tap toggle, Escape, outside click, arrow/Home/End keys, scroll-locked mobile sheet).
- `footer.php` rebuilt to match the Next.js footer; the home page's duplicate CTA band removed.
- Map labels use the same flip rule as the Next.js map.

**Verified locally on WordPress Playground** (`@wp-playground/cli` 3.1.56: PHP 8.2 + SQLite, free ACF from wordpress.org, theme and plugin mounted from this repo):

| Check | Result |
| --- | --- |
| All pages render with no PHP errors, warnings, or notices | Pass |
| `E2E_TARGET=wp` layout audit — 13 routes × 9 widths | 13/13 passed, 0 findings |
| `E2E_TARGET=wp` navigation tests | 13/13 passed |
| PHP syntax (php-parser) | Pass |

**Live on Flywheel** (theme 3.0.0 uploaded through wp-admin, then 3.0.1 adding a fallback site icon; no Primary Navigation menu is assigned, so the built-in nav is used):

| Check | Result |
| --- | --- |
| 15 live pages: HTTP 200, no PHP errors, new header (4 dropdowns) and footer, fonts load | Pass |
| Navigation tests against the live site | 13/13 passed |
| Layout audit against the live site — 13 routes × 9 widths | No layout findings on any route. Some routes still logged a `/favicon.ico` 404 because Flywheel's edge cache was serving pre-3.0.1 HTML (`x-cache: HIT`, `theme.css?ver=3.0.0`); fresh responses include the icon link. Clears when the cache expires or via Clear Cache in the Flywheel dashboard. |

Rollback: re-upload the 2.3.1 theme (`git archive 88a62fc:wordpress/ndses-theme`) through Appearance → Themes → Add New → Upload.


## 7. Precision pass (theme 3.3.0, Oct 22, 2026)

Audited the live site at 1440 / 1024 / 768 / 390 px with full-page screenshots plus an automated measurement script (control heights, sibling heights and element alignment in grid rows, one-word wrap lines, horizontal overflow). Fixes were checked the same way on a local WordPress copy.

| Found on live | Fix |
|---|---|
| ~25 different spacing values (6, 7, 9, 10, 11, 14, 18, 22px…) | 4px spacing scale (`--space-1`…`--space-16`) plus shared `--control-h`, `--card-pad`, `--grid-gap`, `--stack-gap`; every component spacing value now uses them |
| Payment: PayEngine fields 56px vs inputs 48px, different text inset, serif placeholder font | All controls exactly 48px; secure fields 48px box / 46px frame / 14px inset; system sans-serif inside PayEngine frames |
| Payment: 943px form beside a 200px panel; plain radios; every field full width | Method tiles; grouped Account / Card / Bank details; paired rows (account + amount, exp + CVC + ZIP, first + last, routing + account); `$` prefix; sticky side panel with call-the-office line |
| Dumpster cards: titles and truck loads wrapped to 2 lines; Dimensions/Includes rows misaligned | Card-sized type; list grows so notes and buttons align across a row; capped image frame |
| Commercial containers: different card style, 96 Gallon heading 125px higher (no image) | Same card layout as dumpsters; placeholder frame and reserved capacity line when data is missing |
| 5-card and 4-step grids left orphans (4 + 1, 3 + 1) | Five-up rows center the last row; process steps go 1 → 2 → 4 columns |
| Sub-section h2s ("Commercial recycling", "Service Guidelines") as large as section titles; "Contact Card" and "Office Hours" the same size | Column/card headings use `--text-xl`; panel sub-headings `--text-md` |
| Accepted / Not accepted cards different heights; long single-column lists | Side-by-side cards share height; long lists flow into two columns |
| Calculator: result box shorter than form, buttons at different heights, unlabeled size section | Equal-height boxes with centered result; "Compare every dumpster size" heading (editable) |
| Footer hours broke mid-time ("8:00am -" / "4:00pm") | Hours lines never wrap; footer columns rebalanced |
| Contact sidebar was loose paragraphs | Labeled Office / Mailing / Phone / Email list and compact hours list |

Remaining measurement flags are intentional: sticky sidebars and the map list are shorter than the content beside them.

The Next.js app (not live) has not had this pass yet.

## 8. Visual refresh: "clean & light" (theme 3.3.0)

Direction chosen by the client: keep the NDS logo, green and gold, and all content; replace the dated dark-slab look.

- **Tokens:** softer palette (`--green-50`, lighter lines), 16px cards / 24px panels / pill buttons (`--radius-pill`), layered soft shadows (`--shadow-xs`…`--shadow-lg`), focus ring token, larger display type with tighter tracking, more section breathing room.
- **Header:** frosted translucent white bar; pill nav items with a tinted active state; dropdowns as rounded floating panels.
- **Heroes:** light green-tinted wash on every page instead of dark green; Home shows the truck photo as a large rounded image beside the copy (stacks under 1100px); third action is a text link.
- **Section labels:** pill chips with a gold dot instead of uppercase text with a rule.
- **Cards, steps, FAQs, forms:** rounded, soft-shadow surfaces; hover lift on cards that link somewhere; 10px-radius inputs with a soft focus ring; FAQ page uses a pinned category title beside its questions on wide screens.
- **Footer:** the call-to-action is a rounded gradient card straddling the page and the dark footer; mobile footer is shorter (link lists side by side, full-width actions).
- Commercial recycling / industries and community guideline columns are now cards.
