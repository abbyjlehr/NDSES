
$e993e158-72b5-5d8a-9ad2-5d1b2c2164e0$b6049fb0-63b2-4229-ad07-dd93028a9f54 *$ae41a0da-21ec-4387-a93d-020a03b1fa2a2222B$e91c7a6a-417e-5b13-bf85-b748f14ede6fJΗ•³ΧΠεΰ‹Κ	N
L
$83085ba2-f07f-5cd7-b938-e0ef136be0e7$a7a6c844-05b2-5457-b2ba-5ddb8d38dc9d²βJ
έJ# Plan: Load the site's content into WordPress

## Problem

The live site on Flywheel renders from fallback content compiled into the theme. The WordPress admin screens the client was handed (Site Settings, Dumpster Sizes, Commercial Containers, FAQs, Service Areas, Communities) are empty. When the client opens any of them they see nothing to edit, and the handoff doc has to warn them to enter every size before publishing one, because the first published entry hides all the built-in ones. The site looks finished but is not yet in the client's hands.

## Why now

Client approval and launch (FEAT-5) depend on the client being able to see and correct their own content. Full community editing (FEAT-6) needs Community posts to exist before it can add fields to them.

## Who cares

NDSES staff, who were told everything is editable in admin. Abigail, who otherwise has to hand-enter roughly 60 records on Flywheel. The client review pass (FEAT-4), which should review WordPress content, not code.

## Where this sits

Ticket FEAT-1. Continues the theme's existing design, in which WordPress content wins and the seed only fills gaps. Blocks FEAT-6 and FEAT-5.

## Technical headline

Add a one-time, re-runnable content importer to the `ndses-cms` plugin, run from an admin-only page in wp-admin, that creates posts and settings from the theme's existing seed data and attaches the seed images from the Media Library.

## Decisions already made

- **Importer, not hand entry.** Hand entry has no reviewable artifact and is error-prone. Deleting the seed was rejected: the site would render empty for any unpublished type, and the handoff doc promises the fallback behaviour.
- **Admin page is the primary trigger.** Flywheel offers no shell, and the theme and plugin reach it by SFTP or zip upload through wp-admin. A WP-CLI command is included only as a convenience for local runs and must share the same code path.
- **Seed stays in code.** Fallbacks remain the safety net. The importer reads from the seed so the two cannot drift at import time.
- **Client-dependent content is a Task, not a blocker.** The 2 Yard dimensions, 8 Yard "Ideal For" text, 96 Gallon graphic, real Facebook and Instagram URLs, and team names and photos are entered by Abigail in admin after import, once the client answers. The importer loads today's values for those fields.
- **Communities get posts with today's three editable fields only.** Everything else about a community stays seed-backed until FEAT-6.
- **Ships after FEAT-2.** The Service Area form's Latitude and Longitude fields come from FEAT-2's plan, so this importer is implemented once FEAT-2 has shipped and writes those fields, not the old Map X/Y.
- **Out of scope:** Service Notices (client creates these as needed), page-section copy (already pre-filled as ACF defaults), map coordinates (FEAT-2), legal pages and form keys (FEAT-3), Next.js mock data.

Open question, not blocking: whether the Flywheel site has a staging environment. If it does, the import is run there first. If not, it runs on the live site, which is acceptable because the import only adds content that matches what the site already shows.

## Implementation

### Surface

- `wordpress/ndses-cms/ndses-cms.php`: register the admin page and the import entry point. Bump the plugin version.
- A new file under `wordpress/ndses-cms/` holding the importer, loaded by the plugin.
- `docs/EDITOR_GUIDE.md` and `docs/NDSES_HANDOFF.md`: one short note each that the content is already loaded, and remove the "enter all sizes before publishing" warning.
- `wordpress/README.md`: add "run the content import" as a setup step after ACF sync.

The importer reads the seed through the theme's `ndses_data()` and `ndses_faq_seed()`, so it requires the theme to be active and must fail with a clear message if those functions are missing.

### Required behaviour

**Admin page.** A submenu under Site Settings, visible only to users with `manage_options`, with one button. The request is nonce-protected and runs the import synchronously. The page then shows a per-type summary: created, already present, skipped, and any errors, with the reason for each skip or error.

**Idempotence.** Running the import twice leaves the site unchanged. Every created post carries a meta marker holding its seed key. A post is "already present" when a post of that type with that marker exists, whatever its status. The importer never updates or deletes an existing post, so edits the client makes after import survive a re-run. Settings are written only where the stored value is empty.

**What it creates.** All posts are published, with menu order matching seed order:

| Type | Source | Title | Fields written |
| --- | --- | --- | --- |
| `dumpster_size` | `dumpsters` seed, 5 entries | `name` | `truck_loads` from `capacity`, `dimensions`, `ideal_uses` from `uses` (one per line), `included` (one per line), `field_ndses_image` from `image` |
| `commercial_container` | `commercial_containers` seed, 5 entries | `name` | `body`, `dimensions`, `capacity`, `ideal_for` from `ideal` (one per line), `field_ndses_container_image` from `image` when present |
| `faq` | `ndses_faq_seed()`, 21 entries | question | `question`, `answer` as a single paragraph, `category` mapped from the group label (Dumpster Rentals β†’ `dumpster`, Residential Trash & Recycling β†’ `residential`, Commercial Trash & Recycling β†’ `commercial`), `sortOrder` as position within its group |
| `service_area` | `service_areas` seed, 9 entries | `name` | `county`, `service_types` mapped from labels (Residential β†’ `residential`, Commercial β†’ `commercial`, Roll-off β†’ `rollOff`), and for the five towns `latitude` and `longitude` from the `places` table in `scripts/build-service-area-map.cjs` (counties get no coordinates). The seed's `x`/`y` values are not written. |
| `community` | `communities` seed, 5 entries | `name` | post slug equal to the seed key, `service_day`, `trash_schedule`, `recycling_schedule` |

Site Settings, stored under ACF post id `ndses-site-settings`, written from the `settings` seed and the mission and story text: `company_name`, `legal_name`, `phone`, `email`, `address`, `mailing_address`, `hours` (one line per row), `service_area`, `facebook`, `instagram`, `mission`, `our_story` (seed paragraphs wrapped as HTML paragraphs). `header_logo`, `footer_logo`, `footer_description`, and `copyright_text` are left empty: the theme already renders them from its own defaults and nothing in the seed defines them.

**Images.** Each referenced seed image is copied from the theme's `assets/images` folder into the Media Library once, keyed by filename through a meta marker on the attachment, and its attachment id is written to the image field. A missing file is reported as a skip for that field and does not stop the post.

### Invariants

- The theme's fallback behaviour is unchanged: `inc/data.php` and `inc/cms.php` are not modified.
- The importer writes through ACF's own API using the field keys listed above, so values appear in the admin forms and are read back by the existing theme getters without new code paths.
- Rendering after import matches rendering before import for every page except where the admin form adds something the fallback lacked (none expected).

### Scope fence

No new ACF fields. No changes to templates. No deletion or editing of existing posts. No Service Notices, documents, schedule items, or services. No Next.js changes.

## Done means

Every public page on the Flywheel site renders from published WordPress entries, and the client can open Site Settings, Dumpster Sizes, Commercial Containers, FAQs, Service Areas, and Communities in admin and see their real content ready to edit.

## Verification

There is no PHP test harness in this repository, so verification is manual on a local WordPress with ACF free and the theme active, then repeated on Flywheel.

1. Before import, capture the HTML of the home, residential, each community, commercial, dumpster rentals, FAQs, about, and contact pages.
2. Run the import from the admin page. The summary shows the expected created counts and zero errors.
3. Each admin screen lists the entries with fields and images populated.
4. Capture the same pages again. Apart from image URLs, which now point at the Media Library, the rendered content is identical.
5. Run the import again. The summary shows every item as already present and no new posts or attachments exist.
6. Edit one FAQ in admin, run the import a third time, and confirm the edit survives.
7. A non-admin user cannot see the page or trigger the import.

## After it ships

- **Target:** the Flywheel site, after uploading the new plugin version through wp-admin or SFTP.
- **Deployment evidence:** Plugins screen shows the new plugin version, and the import page appears under Site Settings.
- **Outcome evidence:** the import summary on Flywheel reports the expected counts, each admin screen lists the content, and the live pages still render the same content after Flywheel's cache is cleared.
- **Conditions:** ACF field groups must be synced on Flywheel before running the import, or the fields will not save. Clear the Flywheel cache before comparing pages.
- **Safety:** the import is run by Abigail, not automatically. If a staging site exists it runs there first.
- **Follow-up Task for Abigail:** enter the client-supplied answers (2 Yard dimensions, 8 Yard Ideal For, 96 Gallon graphic, Facebook and Instagram URLs, team names and photos) directly in admin once the client provides them.
 