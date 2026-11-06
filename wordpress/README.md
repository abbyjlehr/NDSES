# NDSES WordPress Build

This folder contains the WordPress implementation path for the NDS Environmental Solutions site.

## Install on Flywheel

1. Install WordPress on Flywheel.
2. Install and activate ACF Pro.
3. Upload `wordpress/ndses-cms` as a plugin and activate it.
4. Upload `wordpress/ndses-theme` as the active theme.
5. In WordPress admin, go to ACF and sync the local JSON field groups from `wordpress/ndses-cms/acf-json`.
6. Create the public pages with these slugs:
   - `/`
   - `/residential`
   - `/commercial`
   - `/dumpster-rentals`
   - `/dumpster-calculator`
   - `/faqs`
   - `/about`
   - `/contact`
   - `/make-a-payment`
   - `/whats-new`
7. Set the homepage as the static front page.
8. Build the Primary Navigation menu and assign it to the theme's Primary Navigation location.

## Integration Safety

- TODO: ACF - The theme currently has spreadsheet-based fallback content in `wordpress/ndses-theme/inc/data.php`. Replace it progressively with ACF option fields, page fields, and custom post type entries.
- TODO: PayEngine - The payment page is a non-processing placeholder. Do not collect real card or ACH information until PayEngine is connected and tested.
- TODO: Form Backend - Contact/payment forms validate locally only. Connect the final email, CRM, or webhook destination before launch.
- TODO: Facebook - Alerts and social links are modeled, but Facebook auto-posting is not connected.

## Coverage Map

The coverage map is a static SVG of the five service counties plus a zoomed Walworth County map, generated from US Census county outlines by `scripts/build-service-area-map.cjs` (no Google Maps, no API key). The script also writes `assets/images/service-area-detail-bounds.json`, the detail map's size and longitude/latitude range; the theme projects each Service Area's Latitude and Longitude onto the map with it at render time. To change the map, edit the script, re-run it as its header describes, and copy the SVGs it writes into `wordpress/ndses-theme/assets/images/` and `public/nds-assets/` (the bounds file is written there directly). The address check on the Dumpster Calculator matches the county of the typed address against the counties of the published Service Areas.

## Media

The theme includes client-supplied and spreadsheet-extracted images in `wordpress/ndses-theme/assets/images`. Do not replace missing media with copyrighted stock images. When new images are supplied, upload them to the WordPress Media Library and connect them through ACF fields.

